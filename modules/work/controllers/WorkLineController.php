<?php

namespace app\modules\work\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\CaptureWorkStatus;
use app\models\Client;
use app\models\CoverLetter;
use app\models\Document;
use app\models\FrameOrder;
use app\models\Item;
use app\models\Master;
use app\models\Parameter;
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
        $model = new CaptureWorkStatus();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
				//Yii::trace('trying...', 'WorkLineController::actionBulkStatus');
				if(!is_array($model->keylist))
					$model->keylist = explode(',',trim($model->keylist));
				foreach($model->keylist as $id)
					$ignore = $this->changeStatus($id, $model->status);
        } elseif ($model->status == 'FRAMES') {
			//Yii::trace('FRAMES...', 'WorkLineController::actionBulkStatus');
			return $this->redirect(Url::to(['order-frames', 'ids' => implode(',',$model->keylist)]));
		}
		//Yii::trace('no...'.print_r($model->errors, true), 'WorkLineController::actionBulkStatus');
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

}
