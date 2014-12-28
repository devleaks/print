<?php

use app\models\Account;
use app\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Client Accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['action' => Url::to(['bulk-notify'])]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Client Accounts').'</h3>',
	        'before'=> '',
	        'after'=> Html::submitButton('<i class="glyphicon glyphicon-envelope"></i> '.Yii::t('store', 'Send Reminder'),
							['class' => 'btn btn-warning actionButton', 'data-action' => Account::ACTION_SEND_REMINDER])
				,
			'afterOptions' => ['class'=>'kv-panel-after pull-right'],
	        'footer'=> ''
	    ],
		'panelHeadingTemplate' => '{heading}',
		'showPageSummary' => true,
        'columns' => [
			[
	            'label' => Yii::t('store', 'Client'),
				'noWrap' => true,
	            'value' => function ($model, $key, $index, $widget) {
					$client = Client::findOne($model['client_id']);
	                return $client ? $client->nom : '';
	            },
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'tot_amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true
			],
	        [
	            'class' => 'kartik\grid\ActionColumn',
	            'template' => '{pay} {unpaid} {doc} {accnt}',
				'noWrap' => true,
				'hAlign' => GridView::ALIGN_CENTER,
	            'buttons' => [
	                'doc' => function ($url, $model) {
						$url = Url::to(['/order/document/client', 'id' => $model['client_id']]);
	                    return Html::a('<i class="glyphicon glyphicon-shopping-cart"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'View All Client Documents'),
	                    ]);
	                },
	                'accnt' => function ($url, $model) {
						$url = Url::to(['/accnt/account/client', 'id' => $model['client_id']]);
	                    return Html::a('<i class="glyphicon glyphicon-book"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'View Client Account'),
	                    ]);
	                },
	                'pay' => function ($url, $model) {
						$url = Url::to(['/accnt/account/balance', 'id' => $model['client_id']]);
	                    return Html::a('<i class="glyphicon glyphicon-euro"></i>', $url, [
	                        'class' => 'btn btn-xs btn-success',
	                        'title' => Yii::t('store', 'Add Payment'),
	                    ]);
	                },
	                'unpaid' => function ($url, $model) {
						$url = Url::to(['/accnt/bill/client-unpaid', 'id' => $model['client_id']]);
	                    return Html::a('<i class="glyphicon glyphicon-warning-sign"></i>', $url, [
	                        'class' => 'btn btn-xs btn-warning',
	                        'title' => Yii::t('store', 'Unpaid Bills'),
	                    ]);
	                },
				],
			],
			[
        		'class' => '\kartik\grid\CheckboxColumn',
				'checkboxOptions' => function ($model, $key, $index, $column) {
					return ['value' => $model['client_id']];
				},
			],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
