<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Cash;
use app\models\CashSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashController implements the CRUD actions for Cash model.
 */
class CashController extends Controller
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
     * Lists all Cash models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new CashSearch();
       	$searchModel->load(Yii::$app->request->queryParams);

		if(empty($searchModel->payment_date))
			$searchModel->payment_date = date('Y-m-d');
			
		$day_start = $searchModel->payment_date. ' 00:00:00';
		$day_end   = $searchModel->payment_date. ' 23:59:59';
		$payment_date = $searchModel->payment_date;
		$searchModel->payment_date = null;
      		$dataProvider = $searchModel->search($searchModel->attributes);
		$dataProvider->query
			->andWhere(['>=','payment_date',$day_start])
			->andWhere(['<=','payment_date',$day_end]);
		$searchModel->payment_date = $payment_date;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Pdf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cash model.
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
     * Creates a new Cash model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cash();

        if ($model->load(Yii::$app->request->post())) {
			$mode = ($model->mode == $model::DEBIT) ? -1 : 1;
			$model->amount = round($mode * str_replace(',','.',$model->amount_virgule), 2);
			$model->payment_date = date('Y-m-d H:i:s');
			$model->save();
            return $this->redirect(['list']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cash model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			$mode = ($model->mode == $model::DEBIT) ? -1 : 1;
			$model->amount = round($mode * str_replace(',','.',$model->amount_virgule), 2);
			$model->save();
            return $this->redirect(['list']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cash model.
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
     * Finds the Cash model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cash the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cash::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
