<?php

namespace app\modules\stats\controllers;

use app\models\Event;
use app\models\Order;
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
		$q = Order::find()
			->select(['client_id', 'sum(price_htva) as tot_price', 'count(id) as tot_count'])
			->groupBy('client_id')
			->orderBy('tot_price desc')
			->asArray()->all();

        return $this->render('index',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			])
		]);
    }

	/**
	 * select date(created_at) the_date, count(id) 
	 * from document
	 * group by  the_date
	 * order by  the_date
	 */
	public function actionByDay() {
		$q = Order::find()
			->select(['unix_timestamp(date(created_at)) the_date', 'count(id) as tot_count', 'sum(price_htva) as tot_price'])
			->groupBy('the_date')
			->orderBy('the_date')
			->asArray()->all();

        return $this->render('by-day',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'events' => Event::find(),
		]);
	}
}
