<?php

use app\models\Account;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="payment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title"><a name="CASH"></a>'.Yii::t('store', 'Payments').': '.$label.'</h3>',
	        'before'=> false,
	        'after'=> false, // Html::submitButton(Yii::t('store', 'Partial BOM'), ['class' => 'btn btn-primary']),
			'footer' => false,
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
	        [
				'attribute' => 'note',
	            'label' => Yii::t('store', 'Transaction'),
	            'value' => function ($model, $key, $index, $widget) {
						if($model->ref != null) {
							if($account = Account::findOne(['cash_id' => $model->ref]))
	                    		return $account->client->nom;
							else
								return '';
						}
						return $model->note;
	            },
	            'format' => 'raw',
	        ],
			[
				'attribute' => 'date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date);
				},
				'noWrap' => true,
			],
			[
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
