<?php

namespace app\modules\work\controllers;

use Yii;
use app\models\Document;
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;
use app\models\WorkLineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * WorkLineController implements the CRUD actions for WorkLine model.
 */
class WorkLineController extends Controller
{
    public function behaviors()
    {
        return [
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
    public function actionIndex()
    {
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
    public function actionView($id)
    {
        return $this->render('detail', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorkLine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
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
    public function actionUpdate($id)
    {
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
    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = WorkLine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTask($id)
    {
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
    public function actionList($id = 0)
    {
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
    public function actionListTask($id)
    {
		$task = $this->findTask($id);
		
		$searchModel = new WorkLineSearch();
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
    public function actionMine()
    {
        $searchModel = new WorkLineSearch();
        $dataProvider = new ActiveDataProvider([
			'query' => WorkLine::find()
						->andWhere(['updated_by' => Yii::$app->user->id])
						->andWhere(['status' => Work::STATUS_BUSY]),
		]);

        return $this->render('mine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetail($id)
    {
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

    public function changeStatus($id, $newStatus)
    {
        $model = $this->findModel($id);
		$model->setStatus($newStatus);
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionDone($id)
    {
        return $this->changeStatus($id, Work::STATUS_DONE);
    }

    public function actionUndo($id)
    {
        return $this->changeStatus($id, Work::STATUS_TODO);
    }

    public function actionTake($id)
    {
        return $this->changeStatus($id, Work::STATUS_BUSY);
    }
	
    public function actionWarn($id)
    {
        return $this->changeStatus($id, Work::STATUS_WARN);
    }
	
	public function actionBulkStatus()
	{
		if(isset($_POST))
			if(isset($_POST['status'])) {
				$status = $_POST['status'];
				if(Work::isValidStatus($status)) {
					if(isset($_POST['keylist'])) {
						foreach($_POST['keylist'] as $id) {
							$ignore = $this->changeStatus($id, $status);
						}
					}
				}
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
