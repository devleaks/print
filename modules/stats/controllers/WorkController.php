<?php

namespace app\modules\stats\controllers;

use app\models\Item;
use app\models\Work;
use app\models\WorkLine;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class WorkController extends Controller
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
	 *
select DATEDIFF(updated_at, created_at) diff_days, count(id) as tot_count
from work
group by diff_days
order by diff_days desc
	*
	 */
    public function actionIndex()
    {
		$q = Work::find()
			->select(['diff_days' => 'datediff(updated_at,created_at)', 'tot_count' => 'count(id)'])
			->groupBy('diff_days')
			->orderBy('diff_days')
			->asArray()->all();

        return $this->render('index3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'title' => Yii::t('store', 'Durées des travaux')
		]);
    }


	/**
	 *
select DATEDIFF(updated_at, created_at) diff_days, count(id) as tot_count
from work_line
group by diff_days
order by diff_days desc
	*
	 */
    public function actionLines()
    {
		$q = WorkLine::find()
			->select(['diff_days' => 'datediff(updated_at,created_at)', 'tot_count' => 'count(id)'])
			->groupBy('diff_days')
			->orderBy('diff_days')
			->asArray()->all();

        return $this->render('index3',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'title' => Yii::t('store', 'Durées des tâches')
		]);
    }
}
