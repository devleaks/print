<?php

namespace app\modules\stats\controllers;

use app\models\Bootstrap;
use app\models\Work;
use app\models\WorkLine;
use app\models\Document;

use Moment\Moment;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

class DashboardController extends Controller
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

    public function actionIndex()
    {
		$moment = new Moment();
		//$moment->subtractWeeks(2);
		$q = new Query();
		$docs = $q->from('document')
			->select([
				'document_type',
				'status',
				'total_count' => 'count(id)'])
			->andWhere(['>=', 'due_date', $moment->format('Y-m-d')])
			->groupBy('status,document_type')
			;
		$documents = [];
		foreach($docs->each() as $d) {
			if(!isset($documents[$d['document_type']]))
				$documents[$d['document_type']] = [];
			$documents[$d['document_type']][$d['status']] = $d['total_count'];
		}
		
		$works = Work::find()
			->andWhere(['not', ['status' => Work::STATUS_DONE]])
			->andWhere(['<', 'due_date', date('Y-m-d')]);

		Yii::trace('Moment:'.$moment->format('Y-m-d'));
        return $this->render('index', [
			'documents' => $documents,
			'works' => $works
		]);
    }

	/**
	 * O R D E R S
	 */
	public function actionDocuments() {
		$color = Bootstrap::getColors();
		$workColors = Document::getStatusColors();
		$q = new Query();
		$where = Document::getDateClause(0);
		$data = [];
		foreach($q->from('document')
			->select(['status', 'tot_count' => 'count(id)'])
			->andWhere($where)
			->groupBy('status')
			->each() as $w)
			$data[] = [
				'name' => $w['status'],
				'y' => intval($w['tot_count']),
				'color' => $color[$workColors[$w['status']]]
			];

		return json_encode($data);
	}
	
	
	/**
	 *	Lately
	 */
	public function actionByMonth() {
		$archive = new Query();
		$archive->from('document_archive')
				->select([
				'document_type',
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

		$data1 = [];
		foreach($q as $m) {
			if(!isset($data1[$m['year']][$m['document_type']])) $data[$m['year']][$m['document_type']] = [];
			$data1[$m['year']][$m['document_type']][$m['month']] = intval($m['total_amount']);
		}
		ksort($data1);
		
		$data = [];
		foreach($data1 as $k => $v)
			foreach($v as $k1 => $v1) {
				ksort($v1);
				$v2 = [];
				for($i=1;$i<=12;$i++) {
					if(isset($v1[$i])) {
						$v2[$i-1] = ['y' => $v1[$i], 'url' => Url::to(['order/sales', 'type'=> $k1, 'date'=>$k.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)])];
					} else {
						$v2[$i-1] = 0;
					}
				}
				$data[] = [
					'name' => Yii::t('store', $k1).'-'.$k,
					'stack' => $k,
					'data' => $v2
				];
		}

		return json_encode($data);
	}


	/**
	 * W O R K

select sum(dl.quantity), wl.status from work_line wl, document_line dl
where
wl.document_line_id = dl.id
and
work_id in (
select id from work where status in ('TODO', 'BUSY') )
group by (wl.status)

	 */
	protected function workDial($date) {
		$work = Work::find()->select('id')
				->andWhere(['status' => [Work::STATUS_TODO, Work::STATUS_BUSY]]);
		if($date)
			$work->andWhere($date);
		
		$work_lines = new Query();
		$work_lines->from(['work_line', 'document_line'])
					->select([
							'total_count' => 'sum(document_line.quantity)',
							'status' => 'work_line.status',
							'status_order' => 'ELT(FIELD(work_line.status,"TODO", "BUSY", "WARN", "DONE"),1,2,3,4)'
						])// Wow.
					->andWhere('work_line.document_line_id = document_line.id')
					->andWhere(['work_id' => $work])
					->groupBy('work_line.status')
					->orderBy('status_order');
						
		return $work_lines;		
	}
	
	public function actionWorkLines($id = 0) {
		$color = Bootstrap::getColors();
		$workColors = Work::getStatusColors();
		$date_clause = Document::getDateClause(intval($id), 'work');
		$data = [];
		foreach($this->workDial($date_clause)->each() as $w)
			$data[] = [
				'name' => $w['status'],
				'y' => intval($w['total_count']),
				'color' => $color[$workColors[$w['status']]]
			];
		Yii::trace($id.':'.print_r($date_clause, true).': returning '.count($data));
		return json_encode($data);
	}
	
	
}
/* Query Pool

select d.id, d.created_at, min(wl.updated_at), max(wl.updated_at), datediff(min(wl.updated_at),d.created_at), datediff(max(wl.updated_at),d.created_at)
from document d,
work w,
work_line wl
where d.id = w.document_id
and wl.work_id = w.id
group by d.id




*/