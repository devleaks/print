<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;
use app\assets\BeAsset;

use Moment\Moment;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);
BeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$curr_year = date('Y');

$data1 = [];
foreach($dataProvider->allModels as $m) {
	if(!isset($data1[$m['year']])) $data1[$m['year']] = [];
	$data1[$m['year']][$m['month']] = intval($m['total_amount']);
}

ksort($data1);

$data = [];
$data[] = 'Ventes';
$time = [];
$time[] = 'day';
foreach($data1 as $k => $v)
	foreach($v as $k1 => $v1) {
		$data[] = intval($v1);
		$time[] = $k.'-'.$k1.'-01';
}

$this->title = 'Clients NVB - Commandes via internet par mois';
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">
	
	<h1><?= Html::encode($this->title) ?></h1>
	<br/><br/>
	
	<div id="c3chart"></div>

	<?php VarDumper::dumpAsString($data1, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($data, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($time, 4, true) ?>
	
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
			                'format' => new JsExpression('BE.timeFormat("%B %Y")')
			            ]
			     ]
		    ],
			'tooltip' => [
				'format' => [
					'value' =>  new JsExpression('function (value, ratio, id, index) { var format = BE.numberFormat("$,"); return format(value); }')
				]
			]
		] // clientOptions
	
	/*http://jsfiddle.net/3r39gknt/1/
       'data' => [
            'x' => 'x',
            'columns' => [
                ['x', 'week 1', 'week 2', 'week 3', 'week 4'],
                ['Popularity', 10, 20, 30, 50]
            ],
            'colors' => [
                'Popularity' => '#4EB269',
            ],
        ],
        'axis' => [
            'x' => [
                'label' => 'Month',
                'type' => 'category'
            ],
            'y' => [
                'label' => [
                    'text' => 'Popularity',
                    'position' => 'outer-top'
                ],
                'min' => 0,
                'max' => 100,
                'padding' => ['top' => 10, 'bottom' => 0]
            ]
	        ]
	    ] */
	]);?>

</div>

