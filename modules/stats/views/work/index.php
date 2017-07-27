<?php

use app\models\Client;
use yii\helpers\Html;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$keys = [];
$values = [];
$data = [];
foreach($dataProvider->allModels as $m) {
	$data[$m['diff_days'].' d'] = intval($m['tot_count']);
}

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= Highcharts::widget([
		   'options' => [
			'chart' => [
				'type' => 'bar'
        	],
		      'title' => ['text' => Yii::t('store', 'Nb Days')],
		      'xAxis' => [
		         'categories' => array_keys($data)
		      ],
		      'yAxis' => [
		         'title' => ['text' => Yii::t('store', 'Quantity')]
		      ],
		      'series' => [
		         ['name' => 'Item', 'data' => array_values($data)]
		      ]
		   ]
	]);?>


</div>
