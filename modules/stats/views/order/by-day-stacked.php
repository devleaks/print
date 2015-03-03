<?php

use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;

HighchartsAsset::register($this)->withScripts(['highstock']);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Orders by Day');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= Highcharts::widget([
		'options' => [
			'chart' => [
				'type' => 'column'
        	],
	        'plotOptions' => [
	            'series' => [
	                'stacking' => 'normal'
	            ]
	        ],
		    'title' => ['text' => Yii::t('store', 'Orders by Day')],
			'credits' => [
	            'enabled' => false
	        ],
			'xAxis' => [
				'type' => 'datetime',
				'dateTimeLabelFormats' => [
					'day' => '%e %b',
					'week' => '%e %b',
				],
				'title' => ['text' => Yii::t('store', 'Day')]
			],
			'yAxis' => [
				'title' => ['text' => Yii::t('store', 'Total Amount'), 'min' => 0]
			],
			'series' => $series
		]
	]);?>

</div>
