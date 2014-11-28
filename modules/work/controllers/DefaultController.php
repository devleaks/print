<?php

namespace app\modules\work\controllers;

use app\models\Order;
use app\models\OrderSearch;
use yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionSummary()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query
			->andWhere(['!=', 'status', Order::STATUS_CLOSED])
			->orderBy('due_date asc')
		;

        return $this->render('summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
