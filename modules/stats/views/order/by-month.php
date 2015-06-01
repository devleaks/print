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
$data1 = [];
foreach($dataProvider->allModels as $m) {
	if(!isset($data1[$m['year']][$m['document_type']])) $data[$m['year']][$m['document_type']] = [];
	$data1[$m['year']][$m['document_type']][$m['month']] = intval($m['total_amount']);
}

$data = [];
foreach($data1 as $k => $v)
	foreach($v as $k1 => $v1) {
		ksort($v1);
		$v2 = [];
		foreach($v1 as $d)
			$v2[] = $d;
		$data[] = [
			'name' => Yii::t('store', $k1).'-'.$k,
			'stack' => $k,
			'data' => $v2
		];
}


$this->title = Yii::t('store', 'CA Mensuel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php VarDumper::dumpAsString($data, 4, true) ?>
	
	<?= Highcharts::widget([
		'options' => [
		        'chart' => [
		            'type' => 'column'
		        ],
		        'title' => [
		            'text' => $this->title
		        ],
		        'subtitle' => [
		            'text' => $this->title = Yii::t('store', 'Commandes et Ventes Comptoir'),
		        ],
		        'xAxis' => [
		            'categories' => array_values(Enum::monthList()),
		            'title' => [
		                'text' => null
		            ]
		        ],
		        'yAxis' => [
		            'min' => 0,
		            'title' => [
		                'text' => '€',
		                'align' => 'high'
		            ],
		            'labels' => [
		                'overflow' => 'justify'
		            ]
		        ],
		        'tooltip' => [
		            'valueSuffix' => '€'
		        ],
		        'plotOptions' => [
		            'bar' => [
		                'dataLabels' => [
		                    'enabled' => true
		                ]
		            ],
					'column' => [
		                'stacking' => 'normal'
					]
		        ],
		        'legend' => [
		            'layout' => 'vertical',
		            'align' => 'right',
		            'verticalAlign' => 'top',
		            'x' => -40,
		            'y' => 100,
		            'floating' => true,
		            'borderWidth' => 1,
		            'backgroundColor' => '#FFFFFF',
		            'shadow' => true
		        ],
		        'credits' => [
		            'enabled' => false
		        ],
		        'series' => $data
		    ]
	]);?>

</div>
