<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;
use app\assets\BeAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);
BeAsset::register($this);

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
<div class="parameter-index container">

	<h1><?= Html::encode($this->title) ?></h1>
	
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
