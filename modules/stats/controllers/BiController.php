<?php

namespace app\modules\stats\controllers;

use app\models\BiItem;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class BiController extends Controller
{

	public function actionSales() {
        return $this->render('sales',[
			'title' => Yii::t('store', 'Ventes')
		]);
	}


	public function actionItems() {
        return $this->render('items',[
			'title' => Yii::t('store', 'Articles')
		]);
	}

	public function actionWorks() {
        return $this->render('works',[
			'title' => Yii::t('store', 'Travaux')
		]);
	}

}