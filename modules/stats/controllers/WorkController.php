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
			->select(['datediff(updated_at,created_at) as diff_days', 'count(id) as tot_count'])
			->groupBy('diff_days')
			->orderBy('diff_days')
			->asArray()->all();

        return $this->render('index',[
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
			->select(['datediff(updated_at,created_at) as diff_days', 'count(id) as tot_count'])
			->groupBy('diff_days')
			->orderBy('diff_days')
			->asArray()->all();

        return $this->render('index',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			]),
			'title' => Yii::t('store', 'Durées des tâches')
		]);
    }
}
