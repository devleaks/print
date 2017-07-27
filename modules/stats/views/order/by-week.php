<?php

use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use kartik\helpers\Enum;

HighchartsAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// [Date.UTC(2013,5,2),0.7695],
$data1 = [];
foreach($data as $v)
	$data1[] = [
			intval(strtotime($v['avg_date'])*1000),
			floatval($v['avg_amount'])
		];

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

	<?php   VarDumper::dumpAsString($data, 4, true) ?>
	<?php '<hr/>'.VarDumper::dumpAsString($data1, 4, true) ?>
	
	<?= Highcharts::widget([
		'options' => [
		        'chart' => [
		            'type' => 'spline'
		        ],
		        'title' => [
		            'text' => $this->title
		        ],
				'xAxis'=> [
		            'type'=> 'datetime',
		            'dateTimeLabelFormats'=> [
		                'month'=> '%e. %b',
		                'year'=> '%b'
		            ],
		            'title'=> [
		                'text'=> 'Date'
		            ]
		        ],
		        'yAxis'=> [
		            'title'=> [
		                'text'=> 'Amount (€)'
		            ],
		            'min'=> 0
		        ],
				'series'=> [
					[
				            'name'=> $this->title,
				            'data'=> $data1
					]
				],
		]
	]);?>

</div>
