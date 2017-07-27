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

$data1 = [];
foreach($dataProvider->allModels as $m) {
	if(!isset($data1[$m['year']][$m['month']])) $data1[$m['year']][$m['month']] = 0;
	$data1[$m['year']][$m['month']] += intval($m['total_amount']);
}

ksort($data1);

$data = [];
foreach($data1 as $y => $am) {
	$yr = [];
	$yr[] = $y;
	ksort($am);
	for($i=1;$i<=12;$i++) {
		$yr[] = isset($am[$i]) ? $am[$i] : 0;
	}
	$data[] = $yr;
}


$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

	<?php  VarDumper::dumpAsString($data1, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($data, 4, true) ?>
	
	<div id="c3chart"></div>

	<?= Chart::widget([
		'options' => [
	        'id' => 'c3chart'
		],
		'clientOptions' => [
			'data'=> [
		        'columns' => $data
		    ],
		    'axis'=> [
		        'x'=> [
		            'type' => 'category',
		            'categories' => array_values(Enum::monthList())
		        ],
		        'y'=> [
					'label' => "Chiffre d'affaire (€)"
		        ]
		    ]
	    ]
	]);?>

</div>
