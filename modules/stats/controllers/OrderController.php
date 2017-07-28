<?php

namespace app\modules\stats\controllers;

use app\models\Bill;
use app\models\Client;
use app\models\ClientNvb;
use app\models\Document;
use app\models\_DocumentSearch;
use app\models\DocumentSearch;
use app\models\DocumentLine;
use app\models\Event;
use app\models\Order;
use app\models\WebsiteOrder;

use Yii;
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

        return $this->render('by-day3',[
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
				'year' => 'year(created_at)',
				'month' => 'month(created_at)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(price_htva)'
			])
			->groupBy('document_type,year,month');
			
		$q = Document::find()
			->select([
				'document_type',
				'year' => 'year(created_at)',
				'month' => 'month(created_at)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(price_htva)'
			])
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->groupBy('document_type,year,month')
			->union($archive)
			->asArray()->all();

        return $this->render('by-month3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'events' => Event::find(),
			'title' => Yii::t('store', 'Sales (HTVA) by Due Date Month'),
		]);
	}


	/**
	 * SELECT	S1.due_date AS due_date,
				AVG(S2.price_htva) AS avg_prev_week
		  FROM document AS S1, document AS S2
		 WHERE S2.due_date
		BETWEEN (S1.due_date - INTERVAL 7 DAY)
		   AND S1.due_date
		 GROUP BY S1.due_date
	 */
	public function actionByWeek() {
		$data = new Query();
		$q = $data->from([
				'd1' => 'document',
				'd2' => 'document'
			 ])->select([
				'avg_date' => 'd1.due_date',
				'avg_amount' => 'avg(d2.price_htva)',
			 ])->andWhere('d2.due_date BETWEEN (d1.due_date - INTERVAL 7 DAY) AND d1.due_date')
			 ->groupBy('d1.due_date')
			 ->all();

        return $this->render('by-week',[
			'data' => $q,
			'title' => Yii::t('store', 'Moving Average of Sales per Week'),
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
	public function actionBilled() {
		$archive = new Query();
		$archive->from('document_archive')
				->select([
				'document_type' => 'replace(document_type, "ORDER", "BILL")',
				'year' => 'year(due_date)',
				'month' => 'month(due_date)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(price_htva)'
			])
			->groupBy('document_type,year,month');
			
		$q = Document::find()
			->select([
				'document_type',
				'year' => 'year(created_at)',
				'month' => 'month(created_at)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(price_htva)'
			])
			->andWhere(['document_type' => [Document::TYPE_BILL, Document::TYPE_TICKET]])
			->groupBy('document_type,year,month')
			->union($archive)
			->asArray()->all();

        return $this->render('by-month3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'events' => Event::find(),
			'title' => Yii::t('store', 'Billed (HTVA) by Month'),
		]);
	}


	public function actionCa() {
		$q = Document::find()
			->select([
				'document_type',
				'year' => 'year(created_at)',
				'month' => 'month(created_at)',
				'total_count' => 'count(id)',
				'total_amount' => 'sum(price_htva)'
			])
			->andWhere(['document_type' => [Document::TYPE_BILL, Document::TYPE_TICKET, Document::TYPE_CREDIT, Document::TYPE_REFUND]])
			->groupBy('document_type,year,month')
			->asArray()->all();

        return $this->render('by-ca3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'events' => Event::find(),
			'title' => Yii::t('store', "Chiffre d'affaire (HTVA) par mois"),
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
	
	/**
	 * 
	select client_id,
	sum(if(vat_bool = 1, price_htva, price_tvac)) as total_amount,
	count(id) as total_count,
	min(created_at) as date_min,
	max(created_at) as date_max,
	DATEDIFF(max(created_at), min(created_at)) as date_diff,
	DATEDIFF(max(created_at), min(created_at)) / count(id) as avg_day_between_order,
	sum(if(vat_bool = 1, price_htva, price_tvac)) / DATEDIFF(max(created_at), min(created_at)) as avg_amount_per_day
	from document
	where client_id <> 1680
	group by client_id
	having
	total_count > 3
	and
	total_amount > 2000
	 */
	public function actionFrequency() {
		$ccc = Client::auComptoir()->id;
		$q = Order::find()
			->select([
				'client_id',
				'total_amount' => 'sum(if(vat_bool = 1, price_htva, price_tvac))',
				'total_count' => 'count(id)',
				'date_min' => 'min(created_at)',
				'date_max' => 'max(created_at)',
				'date_diff' => 'DATEDIFF(max(created_at), min(created_at))',
				'avg_day_between_order' => 'DATEDIFF(max(created_at), min(created_at)) / count(id)',
				'avg_amount_per_day' => 'sum(if(vat_bool = 1, price_htva, price_tvac)) / DATEDIFF(max(created_at), min(created_at))'
			])
			->andWhere(['not', ['client_id' => $ccc]])
			->groupBy('client_id')
			->andHaving(['>', 'total_amount', 2500])
			->andHaving(['>', 'total_count', 3])
			->orderBy('total_amount desc')
			->asArray()->all();

        return $this->render('frequency',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			])
		]);
		
	}
	
	function reverseType($in) {
		foreach(Document::getDocumentTypes() as $key => $val)
			if($in == $val) return $key;
		return null;
	}
	
	public function actionSales($type, $date) {
		$year  = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$date_from = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01';
		$date_to = date("Y-m-t", strtotime($date_from));
		
		$type2 = $this->reverseType($type);
		
		if($type2 == Document::TYPE_BILL) {
			$q = Bill::find()
				->andWhere(['year(created_at)' => intval($year)])
				->andWhere(['month(created_at)' => intval($month)]);
		} else {
			$q = Document::find()
				->andWhere(['document_type' => $type2])
				->andWhere(['year(created_at)' => intval($year)])
				->andWhere(['month(created_at)' => intval($month)]);
		}
		
        return $this->render('sales',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			]),
			'searchModel' => null
		]);
	}
	
	public function actionByLang() {
		$year = date('Y-m-d H:i:s', strtotime('now - 365 days'));
		$q = Document::find()
			->joinWith('client')
			->select([
				'year' => 'year(due_date)',
				'month' => 'month(due_date)',
				'total_count' => 'count(document.id)',
				'total_amount' => 'sum(price_htva)',
				'client_lang' => 'client.lang',
				'client_id',
			])
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->andWhere(['>', 'document.created_at', $year])
			->groupBy('year,month,client_lang')
			->asArray()->all();

        return $this->render('by-lang',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'title' => Yii::t('store', 'Sales by language'),
		]);
	}
	
	public function actionNvb() {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->query
			->joinWith('client')
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->andWhere(['not', ['document.status' => Document::STATUS_CANCELLED]])
			->andWhere(['document.id' => WebsiteOrder::find()->select('document_id')])
			//->andWhere(['not', ['client.reference_interne' => null]])
			//->andWhere(['not', ['client.reference_interne' => '']])
		;

	    return $this->render('nvb',[
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel
		]);
	}
	
	public function actionNvbByMonth() {
		$q = Document::find()
			->select([
				'year' => 'year(document.created_at)',
				'month' => 'month(document.created_at)',
				'total_amount' => 'sum(document.price_htva)',
			])
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->andWhere(['not', ['document.status' => Document::STATUS_CANCELLED]])
			->andWhere(['or',
				['document.id' => WebsiteOrder::find()->select('document_id')],
				['document.client_id' => ClientNVB::find()->select('no_nvb')]
				])
			//->andWhere(['not', ['client.reference_interne' => null]])
			//->andWhere(['not', ['client.reference_interne' => '']])
			->groupBy('year,month')
			->asArray()->all();
		;
/*		$q = Document::find()
			->select([
				'year' => 'year(document.created_at)',
				'month' => 'month(document.created_at)',
				'total_amount' => 'sum(document.price_htva)',
			])
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->andWhere(['not', ['document.status' => Document::STATUS_CANCELLED]])
			->andWhere(['document.client_id' => ClientNVB::find()->select('no_nvb')])
			->groupBy('year,month')
			->asArray()->all();
		;
*/
	    return $this->render('nvb-by-month3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
		]);
	}
	
	public function actionSales2($lang, $date) {
		$year  = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$date_from = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01';
		$date_to = date("Y-m-t", strtotime($date_from));
		$q = Document::find()
			->andWhere(['document_type' => [Document::TYPE_ORDER, Document::TYPE_TICKET]])
			->andWhere(['month(document.due_date)' => $month])
			->joinWith('client')
			->andWhere(['client.lang' => $lang])
		;
		
        return $this->render('sales',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			]),
			'searchModel' => null
		]);
	}
	
}
