<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Bill;
use app\models\BillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillController implements the CRUD actions for Bill model.
 */
class BillController extends Controller
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
     * Lists all Bill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['!=','status',Bill::STATUS_CLOSED]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bill model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bill();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bill model.
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
     * Deletes an existing Bill model.
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
     * Finds the Bill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	protected function actionSendRemider($id) {
		Yii::trace('BillController::actionSendRemider:'.$id.'.');
	}

	protected function actionUpdateStatus($id, $status) {
		Yii::trace('BillController::actionUpdateStatus:'.$status.'.');
		$model = $this->findModel($id);
		$model->setStatus($status);
	}

	public function actionBulkAction() {
		if(isset($_POST))
			if(isset($_POST['action'])) {
				$action = $_POST['action'];
				if(in_array($action, [Bill::ACTION_PAYMENT_RECEIVED, Bill::ACTION_SEND_REMINDER])) {
					if(isset($_POST['keylist'])) {
						if($action == Bill::ACTION_PAYMENT_RECEIVED) {
							foreach($_POST['keylist'] as $id) {
								$this->actionUpdateStatus($id, Bill::STATUS_CLOSED);
							}
						} else if ($action == Bill::ACTION_SEND_REMINDER) {
							foreach($_POST['keylist'] as $id) {
								$this->actionSendRemider($id);
							}
						}
					}
				}
			}
	}
	
}
