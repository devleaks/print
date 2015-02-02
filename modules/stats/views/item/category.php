<?php

use app\models\Item;
use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$keys = [];
$values = [];
$data = [];
foreach($dataProvider->allModels as $m) {
	if( $label = Item::findOne($m['item_id'])){
			$keys[] = $label->libelle_long;
			$values[] = intval($m['total']);
			$data[$label->libelle_long] = intval($m['total']);
		}
}

$this->title = Yii::t('store', 'By Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_id',
            'total',
        ],
    ]); ?>


	<?= Highcharts::widget([
		   'options' => [
			'chart' => [
				'type' => 'bar'
        	],
		      'title' => ['text' => 'Items'],
		      'xAxis' => [
		         'categories' => array_keys($data)
		      ],
		      'yAxis' => [
		         'title' => ['text' => 'Quantity Bought']
		      ],
		      'series' => [
		         ['name' => 'Item', 'data' => $values]
		      ]
		   ]
	]);?>

</div>
