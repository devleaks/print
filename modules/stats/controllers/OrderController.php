<?php

namespace app\modules\stats\controllers;

use app\models\Client;
use app\models\Event;
use app\models\Order;
use app\models\Document;
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
		$ccc = Client::auComptoir()->id;
		$q = Order::find()
			->select(['client_id', 'tot_price' => 'sum(price_htva)', 'tot_count' => 'count(id)'])
			->andWhere(['not', ['client_id' => $ccc]])
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
			->select(['the_date' => 'unix_timestamp(date(created_at))', 'tot_count' => 'count(id)', 'tot_price' => 'sum(price_htva)'])
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
	 * select document_type,
	       year(due_date) as year,
	       month(due_date) as month,
	       count(*) as total_count,
	       sum(if(vat_bool = 1, price_htva, price_tvac)) as total_amount
	  from document
	 where document_type = 'ORDER'
	 group by year, month
	 */
	public function actionByMonth() {
		$archive = new Query();
		$archive->from('document_archive')
				->select([
				'document_type',
				'year' => 'year(due_date)',
				'month' => 'month(due_date)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(if(vat_bool = 1, price_htva, price_tvac))'
			])
			->groupBy('document_type,year,month');
			
		$q = Document::find()
			->select([
				'document_type',
				'year' => 'year(due_date)',
				'month' => 'month(due_date)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(if(vat_bool = 1, price_htva, price_tvac))'
			])
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->groupBy('document_type,year,month')
			->union($archive)
			->asArray()->all();

        return $this->render('by-month',[
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
		$q->select(['the_date' => 'unix_timestamp(date(document.created_at))', 'the_category' => 'item.yii_category', 'the_amount' => 'sum(document_line.price_htva)'])
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
	
	public function actionExpand() {
		return $this->render('expand');
	}
}
