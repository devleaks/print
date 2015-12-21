<?php

use kartik\helpers\Enum;

use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

HighchartsAsset::register($this);

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
foreach($data1 as $k => $v)
	foreach($v as $k1 => $v1) {
		$data[] = [intval(mktime(0,0,0,$k1,15,$k)*1000), intval($v1)];
		/*[
			'name' => Yii::t('store', $k1).'-'.$k,
			'stack' => $k,
			'data' => $v1
		];*/
}


$this->title = 'Clients NVB - Commandes via internet par mois';
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

	<?php  VarDumper::dumpAsString($data1, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($data, 4, true) ?>
	
	<?= Highcharts::widget([
		'options' => [
		        'chart' => [
		            'type' => 'column'
		        ],
		        'title' => [
		            'text' => $this->title
		        ],
		        'subtitle' => [
		            'text' => null // $this->title = Yii::t('store', 'Clients NVB - Commandes via internet par mois'),
		        ],
				'xAxis' => [
					'type' => 'datetime',
					'dateTimeLabelFormats' => [
						'month' => '%e %b',
					],
					'title' => ['text' => Yii::t('store', 'Mois')]
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
		            'valueSuffix' => ' €'
		        ],
		        'plotOptions' => [
		            'bar' => [
		                'dataLabels' => [
		                    'enabled' => true
		                ]
		            ]/*,
					'series'=> [
		                'cursor'=> 'pointer',
		                'point'=> [
		                    'events'=> [
		                        'click'=> new JsExpression('function () { location.href = this.options.url; }')
		                    ]
		                ]
		            ]*/
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
		        'series' => [ [
						'name' => Yii::t('store', 'Orders by Day'),
						'data' => $data
					]
				]
		    ]
	]);?>

</div>
