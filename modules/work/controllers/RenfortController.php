<?php

namespace app\modules\work\controllers;

use Yii;
use app\models\Master;
use app\models\Parameter;
use app\models\Segment;
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;
use app\models\WorkLineDetail;
use app\models\WorkLineDetailSearch;
use app\models\WorkLineSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RenfortController implements actions for renfort cuts.
 */
class RenfortController extends Controller
{
    public function behaviors() {
        return [
	        'access' => [
	            'class' => 'yii\filters\AccessControl',
	            'ruleConfig' => [
	                'class' => 'app\components\AccessRule'
	            ],
	            'rules' => [
	                [
	                    'allow' => false,
	                    'roles' => ['?']
               		],
					[
	                    'allow' => true,
	                    'roles' => ['admin', 'manager', 'worker'],
	                ],
	            ],
	        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Get started on renforts.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /** ****************************************************************************************************************
     */

	/**
	 * Select refort to cuts
	 */
    public function actionToCut($g = null) {
		$task = Task::findOne(['name' => 'Renforts']);
		$searchModel = new WorkLineDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['work_line.task_id' => $task->id])
							->andWhere(['!=', 'work_line.status', Work::STATUS_DONE])
							->orderBy('work_line.due_date desc');

        return $this->render($g ? 'to-cut-graphic' : 'to-cut', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'task' => $task,
        ]);
    }


	/**
	 * Review and adjust cuts and number of rods
	 */
	public function actionAdjustCuts() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				$inside_chromaluxe = Parameter::getIntegerValue('renfort', 'inside_chromaluxe', 100) / 10;
				$inside_support    = Parameter::getIntegerValue('renfort', 'inside_support', 50) / 10;
				$models = [];
				foreach(WorkLineDetail::find()->where(['id'=>$_POST['selection']])->each() as $r) {
					$dl = $r->getDocumentLine()->one();
					$r->init_delayed($dl->isChromaLuxe() ? 10 : 5);
					$models[] = $r;
				}
		        return $this->render('adjust-cuts', [
		            'dataProvider' => new ArrayDataProvider([
					'allModels' => $models
		        ])]);
			}
	}


	/**
	 * Create masters with cuts in them. Try to reuse old masters first
	 */
	public function actionPrepareCuts() {
		if(isset($_POST['WorkLineDetail'])) {
			$wlds = $_POST['WorkLineDetail'];
			$models = [];
			$dl_ids = [];
			$segments = [];
			
			// all segments to cut
			foreach($wlds as $idx => $wld) {
				$model = new WorkLineDetail($wld);
				$models[] = $model;
				$dl_ids[] = $wld['document_line_id'];

				for($i = 0; $i < $model->cut_width_count; $i++)
					$segments[] = [
						'document_line_id' => $model->document_line_id,
						'work_length' => $model->cut_width,
					];

				for($i = 0; $i < $model->cut_height_count; $i++)
					$segments[] = [
						'document_line_id' => $model->document_line_id,
						'work_length' => $model->cut_height,
					];

			}
			
			// $segments_by_size = uasort($segments, self::sortByLength);

			// delete segments for theses order lines
			foreach(Segment::find()->where(['document_line_id'=>$dl_ids])->each() as $s)
				$s->delete();

			// delete masters used by above, if any
			// @todo: split master that are partially used because of deletion of above segments
			Master::deleteUnusedMasters();

			// We try to find a segment that fits for each unused master
			foreach(Master::getUnusedMasters()->orderBy('work_length')->each() as $m) {
				$best = null;
				$best_idx = null;
				$min  = Parameter::getIntegerValue('renfort', 'master_length', Master::DEFAULT_SIZE);
				foreach($segments as $idx => $segment) {
					if($segment['work_length'] < $m->work_length) {
						$left = $m->work_length - $segment['work_length'];
						if($left < $min) {
							$min = $left;
							$best = $segment;
							$best_idx = $idx;
						}
					}
				}
				if($best != null) {
					Yii::trace('BEST FIT FOUND='.$best['document_line_id'].', idx='.$idx);
					$s = new Segment([
						'master_id' => $m->id,
						'work_length' => $best['work_length'],
						'document_line_id' => $best['document_line_id'],
					]);
					$s->save();
					unset($segments[$idx]);
				} // else, can't fit a segment in master, left for another time.
			}
			
			// for all segments that are left, we place them in a new master
			foreach($segments as $idx => $segment) {
				$m = Master::createNew();
				$s = new Segment([
					'master_id' => $m->id,
					'work_length' => $segment['work_length'],
					'document_line_id' => $segment['document_line_id'],
				]);
				$s->save();
			}

	        return $this->redirect(Url::to(['manipulate-cuts']));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	
	/**
	 * Save cuts (temporarily or definitively).
	 */
	public function actionSaveCuts() {
		$str = 'ok!';
		if(isset($_POST['cuts'])) {
			$new_masters = [];
			$masters = Json::decode($_POST['cuts']);

			foreach($masters as $master) {
				$master_id = str_replace('master-', '', $master['id']);
				if(strpos($master_id, 'new-') === 0) { // new master, we need to created it:
					$m = $this->newMaster();
					$new_masters[$master_id] = $m->id;
					$master_id = $m->id;
				}
				foreach($master['segments'] as $segment) {
					$segment_id = str_replace('R-', '', $segment);
					if( $s = Segment::findOne($segment_id)) {
						$s->master_id = $master_id;
						$s->save();
					}
				}
			}
			
			$master_to_keep = Json::decode($_POST['keeps']);
			foreach($master_to_keep as $master) {
				$master_id = str_replace('master-', '', $master);
				if(strpos($master_id, 'new-') === 0) { // new master, we need to created it:
					$master_id = $new_masters[$master_id];
				}
				if ($m = Master::findOne($master_id))
					$m->split();
			}
			
			// delete masters where id not in (select master_id from segment)
			Master::deleteUnusedMasters();
			
			return Json::encode(['result' => 'WorkLineController::actionSaveCuts: saved '.$str]);
		} else
			return Json::encode(['result' => 'WorkLineController::actionSaveCuts not cuts?']);
	}


	/**
	 * Print cuts to do.
	 */
	public function actionPrintCuts() {
		$used_masters = Segment::find()->select('master_id')->distinct()->column();
        return $this->render('print-cuts', [
            'dataProvider' => new ActiveDataProvider([
				'query' => Master::find()->andWhere(['id'=>$used_masters])
		    ]),
        ]);
	}
	

	/**
	 * When cut, remove from list to cut. Save remaning bit in Master pool.
	 */
	public function actionDeleteCuts() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				$left_size = Parameter::getIntegerValue('renfort', 'min_length', Master::MINIMUM_SIZE) / 10;
				foreach(Master::find()->where(['id'=>$_POST['selection']])->each() as $m) {
					$tot = 0;
					foreach($m->getSegments()->each() as $s) {
						$tot += $s->work_length;
						$s->delete();
					}
					if($tot < ($m->work_length - $left_size)) {
						$m->work_length -= $tot;
						$m->save();
					} else
						$m->delete();
				}
				// HERE: Should complete Renfort task for all WL that have no more segments
			}
        return $this->redirect(Url::to(['print-cuts']));
	}


	public function actionManipulateCuts() {
        return $this->render('manipulate-cuts', [
            'masters' => Master::find(),
        ]);
	}

	
	public function actionListCuts() {
        return $this->render('show-cuts-text', [
            'masters' => Master::find(),
        ]);
	}
	
	
	public static function sortByLength($a, $b) {
		return $a->work_length > $b->work_length;
	}

	
	public function actionSplit($id) {
		return Json::encode(['result' => $id.'-1']);
	}
	
}
