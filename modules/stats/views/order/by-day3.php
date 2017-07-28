<?php

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;
use app\assets\BeAsset;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);
BeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$data = [];
$data[] = 'Ventes';
$time = [];
$time[] = 'day';
foreach($dataProvider->allModels as $m) {
	$data[] = intval($m['tot_price']);
	$time[] = date('Y-m-d', intval($m['the_date']));
}

$this->title = Yii::t('store', 'Orders by Day');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

	<?php VarDumper::dumpAsString($time, 4, true) ?>
	
	<div id="c3chart"></div>

	<?= Chart::widget([
		'options' => [
	        'id' => 'c3chart'
		],
		'clientOptions' => [
			'data' => [
				'x' => 'day',
		        'columns' => [
		            $time,
		            $data
		        ],
		        'type' => 'bar'
		    ],
			'axis' => [
			        'x' => [
			            'type' => 'timeseries',
			            'tick' => [
			                'format' => '%d-%m-%Y'
			            ]
			     ]
			],
			'tooltip' => [
				'format' => [
					'value' =>  new JsExpression('function (value, ratio, id, index) { var format = BE.numberFormat("$,"); return format(value); }')
				]
			]
		]
	]);?>

</div>
