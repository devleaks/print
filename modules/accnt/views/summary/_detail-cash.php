<?php

use app\models\Cash;
use app\models\Document;
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
				'attribute' => 'order',
	            'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
							if($account = Account::findOne(['cash_id' => $model->ref])) {
								$str = '';
								foreach($account->getPayments()->each() as $payment) {
									if($doc = Document::find()->andWhere(['sale' => $payment->sale])->orderBy('created_at desc')->one()) {
										$str .= $doc->name.',';
									}
								}
							}
                    		return rtrim($str,',');
	            },
	        ],
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
			[
				'attribute' => 'solde',
				'format' => 'currency',
            	'label' => Yii::$app->formatter->asCurrency($cash_start),
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => function ($summary, $data, $widget) {
					return Yii::$app->formatter->asCurrency(end($data));
				},
			],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
