<?php

namespace app\modules\stats\controllers;

use app\models\Bootstrap;
use app\models\Work;
use app\models\WorkLine;
use app\models\Document;
use yii\web\Controller;
use yii\db\Query;

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
        return $this->render('index');
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
	 * W O R K
	 */
	public function actionWorkLines($id = 0) {
		$color = Bootstrap::getColors();
		$workColors = Work::getStatusColors();
		$q = new Query();
		$where = Document::getDateClause(intval($id));
		$data = [];
		foreach($q->from('work_line')
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
}
