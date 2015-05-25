<?php

namespace app\modules\stats\controllers;

use app\models\Event;
use app\models\Order;
use app\models\DocumentLine;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class OrderController extends Controller
{
    public function behaviors()
    {
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
	                    'roles' => ['admin', 'manager'],
	                ],
	            ],
	        ],
        ];
    }


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


	/**
	 *
	 */
	public function actionByDayStacked() {
		$q = new Query();
		$q->select(['unix_timestamp(date(document.created_at)) the_date', 'item.yii_category as the_category', 'sum(document_line.price_htva) as the_amount'])
			->from(['document', 'document_line', 'item'])
			->andwhere('document.id = document_line.document_id')
			->andwhere('document_line.item_id = item.id')
			->andWhere(['document.document_type' => Order::TYPE_ORDER])
			->groupBy('the_date,the_category')
			;

		$datapercat = [];
		foreach($q->each() as $m) {
			if(!isset($datapercat[$m['the_category']]))
				$datapercat[$m['the_category']] = [];
			$datapercat[$m['the_category']][] = [intval($m['the_date']*1000), intval($m['the_amount'])];
		}

/*
[ [
					'name' => 'Orders by day',
					'data' => $data
				],
				[
					'type' => 'flags',
					'data' => $evts,
				]
*/
		$series = [];
		foreach($datapercat as $cat => $data)
			$series[] = [
				'name' => $cat,
				'data' => $data,
			];
	
		// Prepare events
		$evts = [];
		foreach(Event::find()->each() as $e)
			$evts[] = [
				'x' => intval(strtotime($e->date_from)*1000),
				'title' => 'E',
				'text' => $e->name
			];
		if(count($evts) > 0)
			$series[] = [
				'type' => 'flags',
				'data' => $evts,
			];

        return $this->render('by-day-stacked',[
			'series' => $series,
		]);
	}
}
