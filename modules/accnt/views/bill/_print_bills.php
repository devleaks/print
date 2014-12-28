<?php

use app\models\Account;
use app\models\Parameter;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

?>
<div class="account-index">

	<h4><?= Yii::t('store', 'Unpaid Bills') ?></h4>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'layout' => '{items}',
		'condensed' => true,
		'striped' => false,
		'pageSummaryRowOptions' => ['class' => 'kv-page-summary'],
	    'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Order'),
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'price_tvac',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Prepaid'),
				'format' => 'currency',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getPrepaid();
				},
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'price_tvac',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getBalance();
				},
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				}
			],
			[
	            'label' => Yii::t('store', 'Delay'),
				'value' => function ($model, $key, $index, $widget) {
					return $model->getDelay('created');
				},
				'hAlign' => GridView::ALIGN_CENTER,
			],
	        [
	            'label' => Yii::t('store', 'Status'),
	            'attribute' => 'status',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getStatusLabel();
	            		},
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
        ],
    ]); ?>

</div>