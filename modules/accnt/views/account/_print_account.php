<?php

use app\models\Account;
use app\models\Parameter;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

Yii::$app->language = ($client->lang ? $client->lang : 'fr');
?>
<div class="account-index">

	<h4><?= Yii::t('store', 'Your Account Summary') ?></h4>

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
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
					if($model->document)
						return $model->document->name;
					else {
						if(    $model->status == Account::TYPE_CREDIT
							&& $model->payment_method == Account::ACTION_TRANSFER
							&& $model->amount > 0) return Yii::t('store', 'Bank Transfer. Thank You.');
						else
							return '';
					} 
	            },
			],
	        [
				'label' => Yii::t('store', 'Order Date'),
				'noWrap' => true,
				'format' => 'date',
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ? new DateTime($model->document->created_at) : null;
	            },
			],
	        [
				'label' => Yii::t('store', 'Order Due Date'),
				'noWrap' => true,
				'format' => 'date',
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ? new DateTime($model->document->due_date) : null;
	            },
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true
			],
	        [
				'attribute' => 'status',
	            'label' => Yii::t('store', 'Account'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			[
	            'label' => Yii::t('store', 'Payment Date'),
				'noWrap' => true,
				'attribute' => 'created_at',
				'format' => 'date',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
			[
	            'label' => Yii::t('store', 'Notes & Comments'),
           		'attribute' => 'note',
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->note ? $model->note : '';
	            },
			],
        ],
    ]); ?>

</div>