<?php

namespace app\modules\stats\controllers;

use app\models\Item;
use app\models\Document;
use app\models\DocumentLine;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class OrderController extends Controller
{
	/**
	 * select client_id, sum(price_htva), count(id)
	 * from document
     * group by client_id
	 */
    public function actionIndex()
    {
		$q = Document::find()
			->select('client_id, sum(price_htva) as tot_price, count(id) as tot_count')
			->groupBy('client_id')
			->orderBy('tot_price desc')
			->asArray()->all();

        return $this->render('index',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			])
		]);
    }
}
