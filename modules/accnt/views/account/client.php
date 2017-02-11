<?php

use app\models\Parameter;
use app\models\User;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;


// @todo invert order on page

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Customer {0}', [$client->niceName()]);
$this->params['breadcrumbs'][] = ['label' => User::hasRole(['manager', 'admin']) ? Yii::t('store', 'Management') : Yii::t('store', 'Accounting'),
								  'url'   => [User::hasRole(['manager', 'admin']) ? '/store' : '/accnt']];
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;
?>
<div class="account-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	    'showPageSummary' => true,
		'afterHeader' => [
			[
				'columns' => [
					['content' => null],
					['content' => null],
					['content' => null],
					['content' => null],
					['content' => null],
					['content' => Yii::$app->formatter->asCurrency($bottomLine), 'options' => ['class' => 'kv-align-right kv-nowrap']]
				],
				'options' => ['class' => 'kv-page-summary warning']
			]
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//			'ref',
			[
	            'label' => Yii::t('store', 'Debit'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
				'value' => function ($model, $key, $index, $widget) {
					return $model->amount < 0 ? $model->amount : '';
				}
			],
			[
	            'label' => Yii::t('store', 'Credit'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
				'value' => function ($model, $key, $index, $widget) {
					return $model->amount > 0 ? $model->amount : '';
				}
			],
			[
				'attribute' => 'note',
				'format' => 'raw',
			],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date);
				}
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'account',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => null,
			],
//            [
//				'class' => 'kartik\grid\ActionColumn',
//			 	'template' => '{update} {delete}'
//			],
        ],
    ]); ?>


	<?= Html::a('<i class="glyphicon glyphicon-print"></i> '.Yii::t('store', 'Print'), Url::to(['client-print', 'id' => $client->id]), ['class' => 'btn btn-primary store-action', 'target' => '_blank']) ?>


</div>
