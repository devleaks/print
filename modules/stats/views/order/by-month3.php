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
	if(!isset($data1[$m['year']][$m['document_type']])) $data[$m['year']][$m['document_type']] = [];
	$data1[$m['year']][$m['document_type']][$m['month']] = intval($m['total_amount']);
}

ksort($data1);

$data = [];
$groups = [];

foreach($data1 as $k => $v) {
	$yr = [];
	foreach($v as $k1 => $v1) {
		ksort($v1);
		$v2 = [];
		$v2[] = $k.'-'.Yii::t('store', $k1); // 2014-TICKET
		for($i=1;$i<=12;$i++) {
			$v2[] = isset($v1[$i]) ? $v1[$i] : 0;
		}
		$data[] = $v2;
		$yr[] = $v2[0];	
	}
	$groups[] = $yr;
}


$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">
	
	<div id="c3chart"></div>

	<?php VarDumper::dumpAsString($data1, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($data, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($groups, 4, true) ?>
	
	<?= Chart::widget([
		'options' => [
            'id' => 'c3chart'
		],
		'clientOptions' => [
			'data'=> [
		        'columns' => $data,
		        'type' => 'bar',
		        'groups' => $groups,
		        'order' => null,
				'onclick' => new JsExpression('function (d, element) {
						function encodeData(data) {
						    return Object.keys(data).map(function(key) {
						        return [key, data[key]].map(encodeURIComponent).join("=");
						    }).join("&");
						}
						var dd = d["name"].substr(0,4) + "-" + ("00" + (parseInt(d["x"]) + 1)).slice(-2);
						var dt = d["name"].substr(d["name"].indexOf("-")+1);
						var url = "sales?" + encodeData({type: dt, date: dd});
						// console.log(dd, dt, url);
						window.location = url;
					}')
		    ],
		    'axis'=> [
		        'x'=> [
		            'type' => 'category',
		            'categories' => array_values(Enum::monthList())
		        ],
		        'y'=> [
					'label' => "Chiffre d'affaire (€)"
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
