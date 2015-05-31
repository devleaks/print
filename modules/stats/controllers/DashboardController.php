<?php

namespace app\modules\stats\controllers;

use Yii;
use app\models\Bootstrap;
use app\models\Work;
use app\models\WorkLine;
use app\models\Document;
use yii\web\Controller;
use yii\db\Query;
use Moment\Moment;

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
		$last_week = new Moment();
		$last_week->subtractWeeks(1);
		$q = new Query();
		$docs = $q->from('document')
			->select([
				'document_type',
				'status',
				'total_count' => 'count(id)'])
			->andWhere(['>', 'due_date', $last_week->format('Y-m-d')])
			->groupBy('status,document_type')
			;
		$documents = [];
		foreach($docs->each() as $d) {
			if(!isset($documents[$d['document_type']]))
				$documents[$d['document_type']] = [];
			$documents[$d['document_type']][$d['status']] = $d['total_count'];
		}

		Yii::trace('Moment:'.$last_week->format('Y-m-d'));
        return $this->render('index', [
			'documents' => $documents
		]);
    }

    public function actionTest()
    {
        return $this->render('test');
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
			->select(['status', 'count(id) as tot_count'])
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

		$data1 = [];
		foreach($q as $m) {
			if(!isset($data1[$m['year']][$m['document_type']])) $data[$m['year']][$m['document_type']] = [];
			$data1[$m['year']][$m['document_type']][$m['month']] = intval($m['total_amount']);
		}

		$data = [];
		foreach($data1 as $k => $v)
			foreach($v as $k1 => $v1) {
				ksort($v1);
				$v2 = [];
				foreach($v1 as $d)
					$v2[] = $d;
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
						])
					->andWhere('work_line.document_line_id = document_line.id')
					->andWhere(['work_id' => $work])
					->groupBy('work_line.status');
						
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
