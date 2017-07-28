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

$keys = [];
$keys[] = 'day';
$data = [];
$data[] = 'count';
foreach($dataProvider->allModels as $m) {
	$keys[] = $m['diff_days'].' d';
	$data[] = intval($m['tot_count']);
}

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

	<h1><?= Html::encode($this->title) ?></h1>
	<br/><br/>
	
	<?php  VarDumper::dumpAsString($keys, 4, true) ?>
	<?php  '<hr/>'.VarDumper::dumpAsString($data, 4, true) ?>
	
	<div id="c3chart"></div>

	<?= Chart::widget([
		'options' => [
	        'id' => 'c3chart'
		],
		'clientOptions' => [
			'data' => [
				'x' => 'day',
		        'columns' => [
		            $keys,
		            $data
		        ]
		    ],
	    'axis'=> [
	        'x'=> [
	            'type' => 'category',
	            'categories' => $keys,
				'max' => 20
	        ]
	    ]
	    ]
	]);?>

</div>