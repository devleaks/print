<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$groups = [];
foreach($dataProvider->query->each() as $m) {
	if($m['name'] == '') $m['name'] = 'Sans catégorie';
	$groups[] = [$m['name'], intval($m['tot_count'])];
}

$groups2 = [];
foreach($dataProvider2->query->each() as $m) {
	if($m['name'] == '') $m['name'] = 'Sans catégorie';
	$groups2[] = [$m['name'], intval($m['tot_count'])];
}

$this->title = Yii::t('store', 'Items');
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

	<?php  VarDumper::dumpAsString($groups, 4, true) ?>
	<?php  VarDumper::dumpAsString($groups2, 4, true) ?>
	
	<h2>Quantités</h2>
	
	<div id="c3chart"></div>

	<?= Chart::widget([
		'options' => [
	        'id' => 'c3chart'
		],
		'clientOptions' => [
			'data'=> [
		        'columns' => $groups,
				'type' => 'pie'
		    ],
			'legend' => [
				'show' => false
			],
			'pie' => [
				'label' => [
					'format' => new JsExpression('function (value, ratio, id) { return id; }'),
					'threshold' => 0.05
				]
			]
	    ]
	]);?>

	<h2>Chiffre d'affaire</h2>
	
	<div id="c3chart2"></div>

	<?= Chart::widget([
		'options' => [
	        'id' => 'c3chart2'
		],
		'clientOptions' => [
			'data'=> [
		        'columns' => $groups2,
				'type' => 'pie'
		    ],
			'legend' => [
				'show' => false
			],
			'pie' => [
				'label' => [
					'format' => new JsExpression('function (value, ratio, id) { return id; }'),
					'threshold' => 0.02
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
