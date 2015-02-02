<?php

namespace app\modules\work\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Client;
use app\models\CoverLetter;
use app\models\Document;
use app\models\FrameOrder;
use app\models\Item;
use app\models\Master;
use app\models\Provider;
use app\models\Segment;
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;
use app\models\WorkLineDetail;
use app\models\WorkLineDetailSearch;
use app\models\WorkLineSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * WorkLineController implements the CRUD actions for WorkLine model.
 */
class WorkLineController extends Controller
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
     * Lists all WorkLine models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new WorkLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['!=', 'work_line.status', Work::STATUS_DONE]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkLine model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('detail', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorkLine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new WorkLine();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WorkLine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WorkLine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WorkLine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkLine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = WorkLine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTask($id) {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Lists all Work models for given date.
     * @return mixed
     */
    public function actionList($id = 0) {
		$where = Document::getDateClause(intval($id));
	
        $searchModel = new WorkLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere($where)
						->andWhere(['!=', 'work_line.status', Work::STATUS_DONE]);

        return $this->render('list-by-date', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'day' => $id,
        ]);
    }


    /**
     * Lists all Work models for given date.
     * @return mixed
     */
    public function actionListTask1($id) {
		$task = $this->findTask($id);
		
		$searchModel = new WorkLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['work_line.task_id' => $id])
							->andWhere(['!=', 'work_line.status', Work::STATUS_DONE]);

        return $this->render('list-by-type1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'task' => $task,
        ]);
    }

    /**
     * Lists all Work models for given date.
     * @return mixed
     */
    public function actionListTask($id) {
		$task = $this->findTask($id);
		
		$searchModel = new WorkLineDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['work_line.task_id' => $id])
							->andWhere(['!=', 'work_line.status', Work::STATUS_DONE]);

        return $this->render('list-by-type', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'task' => $task,
        ]);
    }


    /**
     * Lists all open WorkLine models.
     * @return mixed
     */
    public function actionMine() {
        $searchModel = new WorkLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
						->andWhere(['work_line.updated_by' => Yii::$app->user->id])
						->andWhere(['work_line.status' => Work::STATUS_BUSY]);

        return $this->render('mine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetail($id) {
        $model = $this->findModel($id);
		$old_status = $model->status;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if( $old_status != $model->status )
				$model->setStatus($model->status);
			Yii::$app->session->setFlash('success', Yii::t('store', 'Updated.'));
        }
        return $this->render('detail', [
            'model' => $model,
        ]);
    }

    public function changeStatus($id, $newStatus) {
        $model = $this->findModel($id);
		$model->setStatus($newStatus);
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionDone($id) {
        return $this->changeStatus($id, Work::STATUS_DONE);
    }

    public function actionUndo($id) {
        return $this->changeStatus($id, Work::STATUS_TODO);
    }

    public function actionTake($id) {
        return $this->changeStatus($id, Work::STATUS_BUSY);
    }
	
    public function actionWarn($id) {
        return $this->changeStatus($id, Work::STATUS_WARN);
    }
	
	public function actionBulkStatus() {
		if(isset($_POST))
			if(isset($_POST['status'])) {
				$status = $_POST['status'];
				if(Work::isValidStatus($status)) {
					if(isset($_POST['keylist'])) {
						foreach($_POST['keylist'] as $id) {
							$ignore = $this->changeStatus($id, $status);
						}
					}
				} else if($status == 'FRAMES')
					return $this->redirect(Url::to(['order-frames', 'ids' => implode(',',$_POST['keylist'])]));
			}
	}
	
	
	protected function generateFrameOrders($ids) {
		$providers = [];
		foreach(Provider::find()->each() as $p)
			$providers[$p->name] = $p->email;

		$frames = [];
		foreach(WorkLine::find()->where(['id' => $ids])->each() as $wl) {
			if ( $dl = $wl->getDocumentLine()->one() )
				if ( $dld = $dl->getDetail() )	
					if($dld->frame_id > 0) {
						$frame = Item::findOne($dld->frame_id);
						if(in_array($frame->fournisseur, array_keys($providers))) {
							$frames[] = new FrameOrder([
								'work_line_id' => $wl->id,
								'reference' => $dl->document->name,
								'due_date' => $dl->due_date,
								'provider' => $frame->fournisseur,
								'provider_email' => $providers[$frame->fournisseur],
								'item' => $frame->libelle_long.( $frame->reference_fournisseur ? ' ('.$frame->reference_fournisseur.')' : '' ),
								'width' => $dl->work_width,
								'height' => $dl->work_height,
								'quantity' => $dl->quantity,
								'note' => $wl->note,
							]);
						}
					}
			// $wl->setStatus(Work::STATUS_BUSY); // ?
		}
		return count($frames) > 0 ? $frames : null;
	}


	public function actionOrderFrames($ids) {
		$ids2 = explode(',',$ids);
		
		if($frames = $this->generateFrameOrders($ids2)) {
	        return $this->render('frame-orders', [
	            'dataProvider' => new ArrayDataProvider(['allModels' => $frames]),
	            'ids' => $ids,
	        ]);
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'There are no frame ready to order.'));
			return $this->redirect(Url::to(['index']));
		}
	}


	public function actionSendOrders($ids) {
		$ids2 = explode(',',$ids);
		
		if($frames = $this->generateFrameOrders($ids2)) {
			$providers = [];
			$frames_by_provider = [];
			foreach(Provider::find()->each() as $p) {
				$providers[$p->name] = $p->email;
				$frames_by_provider[$p->name] = [];
			}

			foreach($frames as $frame) // group frames by provider
				$frames_by_provider[$frame->provider][] = $frame;
			
			foreach($frames_by_provider as $provider => $frameset) {
				if(count($frameset) > 0) {		
					$viewBase = '@app/modules/store/prints/frame-order/';
				    $table = $this->renderPartial($viewBase.'frame-orders', ['frames' => $frameset]);

					$coverLetter = new CoverLetter([
						'type'		=> 'FRAME_ORDERS',
						'client'	=> new Client([ // should be enought...
										'autre_nom' => $provider,
										'email' => $providers[$p->name],
										'prenom' => '',
										'nom' => '',
										'adresse' => '',
										'code_postal' => '',
										'localite' => '',
										'pays' => 'Belgique'
									   ]),			
						'date'		=> date('d/m/Y', strtotime('now')),			
						'subject'	=> Yii::t('store', 'Frame order for Labo JJ Micheli'),			
						'body'		=> Yii::t('store', 'Please read attached document(s).'),
						'table'		=> $table, 			
						'watermark' => false,			
						'viewBase' 	=> null,			
						'save'		=> true,
						'destination' => RuntimeDirectoryManager::FRAME_ORDERS,
					]);
		
					$coverLetter->render();
					$coverLetter->send();
					
					foreach($frameset as $frame) {
						$wl = WorkLine::findOne($frame->work_line_id);
						$wl->setStatus(Work::STATUS_BUSY); //STATUS_DONE?
					}
				}
			}
			Yii::$app->session->setFlash('success', Yii::t('store', 'Orders sent.'));
			return $this->redirect(Url::to(['/work']));
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'There are no frame ready to order.'));
			return $this->redirect(Url::to(['/work']));
		}
	}


	public static function getBadge($id) {
		$where = Document::getDateClause(intval($id));
		$all= $id = -2
			?   self::find()->count()
			:	self::find()
					->andWhere($where)
					->count();
		$notfinished= $id = -2
			?   self::find()->count()
			:	self::find()
					->andWhere($where)
					->andWhere(['!=', 'status', self::STATUS_TODO])
					->count();
		return $all.' / '.$notfinished;
	}

    /**
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



	public function actionAdjustCuts() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
		        $dataProvider = new ActiveDataProvider([
					'query' => WorkLineDetail::find()->where(['id'=>$_POST['selection']])
		        ]);
		        return $this->render('adjust-cuts', [
		            'dataProvider' => $dataProvider,
		        ]);
			}
	}


	public function actionDeleteCuts() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				foreach(Master::find()->where(['id'=>$_POST['selection']])->each() as $m) {
					foreach($m->getSegments()->each() as $s)
						$s->delete();
					$m->delete();
				}
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
	
	
	public function actionPrintCuts() {
		$used_masters = Segment::find()->select('master_id')->distinct()->column();
        return $this->render('print-cuts', [
            'dataProvider' => new ActiveDataProvider([
				'query' => Master::find()->andWhere(['id'=>$used_masters])
		    ]),
        ]);
	}
	
	public static function sortByLength($a, $b) {
		return $a->work_length > $b->work_length;
	}

	
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
				$min  = Master::DEFAULT_SIZE;
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
			
			echo Json::encode(['result' => 'WorkLineController::actionSaveCuts: saved '.$str]);
		} else
			echo Json::encode(['result' => 'WorkLineController::actionSaveCuts not cuts?']);
	}


	public function actionSplit($id) {
		echo Json::encode(['result' => $id.'-1']);
	}
	
}
