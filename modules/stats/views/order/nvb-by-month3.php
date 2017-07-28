<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;

use Moment\Moment;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);

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
<script type="text/javascript">
<?php $this->beginBlock('JS_C3_LOCALE'); ?>
var BE = d3.locale ({
	  "decimal": ",",
	  "thousands": ".",
	  "grouping": [3],
	  "currency": ["", " €"],
	  "dateTime": "%a %b %e %X %Y",
	  "date": "%d/%m/%Y",
	  "time": "%H:%M:%S",
	  "periods": ["AM", "PM"],
	  "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
	  "shortDays": ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
	  "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
	  "shortMonths": ["Janv", "Févr", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
	});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_C3_LOCALE'], yii\web\View::POS_END);
?>
<div class="parameter-index container">
	
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
<script type="text/javascript">
<?php $this->beginBlock('JS_C3_LOCALE'); ?>
var BE = d3.locale ({
	  "decimal": ",",
	  "thousands": ".",
	  "grouping": [3],
	  "currency": ["", " €"],
	  "dateTime": "%a %b %e %X %Y",
	  "date": "%d/%m/%Y",
	  "time": "%H:%M:%S",
	  "periods": ["AM", "PM"],
	  "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
	  "shortDays": ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
	  "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
	  "shortMonths": ["Janv", "Févr", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
	});
console.log(BE);
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_C3_LOCALE'], yii\web\View::POS_END);

