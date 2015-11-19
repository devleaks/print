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
	if(!isset($data1[$m['year']][$m['client_lang']])) $data[$m['year']][$m['client_lang']] = [];
	$data1[$m['year']][$m['client_lang']][$m['month']] = intval($m['total_amount']);
}

ksort($data1);

$data = [];
foreach($data1 as $k => $v)
	foreach($v as $k1 => $v1) {
		ksort($v1);
		$v2 = [];
		for($i=1;$i<=12;$i++) {
			if(isset($v1[$i])) {
				$v2[$i-1] = ['y' => $v1[$i], 'url' => Url::to(['sales2', 'lang'=> $k1, 'date'=>$k.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)])];
			} else {
				$v2[$i-1] = 0;
			}
		}
		$data[] = [
			'name' => Yii::t('store', $k1).'-'.$k,
			'stack' => $k,
			'data' => $v2
		];
}


$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
					],
					'series'=> [
		                'cursor'=> 'pointer',
		                'point'=> [
		                    'events'=> [
		                        'click'=> new JsExpression('function () { location.href = this.options.url; }')
		                    ]
		                ]
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
