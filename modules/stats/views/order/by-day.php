<?php

use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;

HighchartsAsset::register($this)->withScripts(['highstock']);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$data = [];
foreach($dataProvider->allModels as $m)
	$data[] = [intval($m['the_date']*1000), intval($m['tot_price'])];

$evts = [];
foreach($events->each() as $e)
	$evts[] = [
		'x' => intval(strtotime($e->date_from)*1000),
		'title' => 'E',
		'text' => $e->name
	];


$this->title = Yii::t('store', 'Orders by Day');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo '';/* GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'item_id',
            'total',
        ],
    ]); */ ?>
	
	<?= Highcharts::widget([
		'options' => [
			'chart' => [
				'type' => 'column'
        	],
		    'title' => ['text' => Yii::t('store', 'Orders by Day')],
			'xAxis' => [
				'type' => 'datetime',
				'dateTimeLabelFormats' => [
					'day' => '%e %b',
					'week' => '%e %b',
				],
				'title' => ['text' => Yii::t('store', 'Day')]
			],
			'yAxis' => [
				'title' => ['text' => Yii::t('store', 'Total Amount'), 'min' => 0]
			],
	        'credits' => [
	            'enabled' => false
	        ],
			'series' => [ [
					'name' => Yii::t('store', 'Orders by Day'),
					'data' => $data
				],
				[
					'name' => Yii::t('store', 'Events'),
					'type' => 'flags',
					'data' => $evts,
				]
			]
		]
	]);?>

</div>
