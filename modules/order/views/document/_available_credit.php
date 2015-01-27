<?php

use app\models\Payment;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
$dataProvider = new ArrayDataProvider([
	'allModels' => $client->getCreditLines(),
	'pagination' => false,
]);
?>
<div class="credit-index">
	<h4><?= Yii::t('store', 'Available credits') ?></h4>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'showPageSummary' => true,
		'layout' => '{items}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date);
				},
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
	        [
				'attribute' => 'note',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
        ],
    ]); ?>

</div>
