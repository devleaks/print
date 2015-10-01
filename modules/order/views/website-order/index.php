<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use app\models\User;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Web Transfers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [User::hasRole(['manager', 'admin']) ? '/store' : '/order', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title)?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

	        [
				'attribute' => 'order_date',
			],
	        [
				'attribute' => 'name',
				'noWrap' => true,
			],
	        [
				'attribute' => 'company',
				'noWrap' => true,
			],
	        [
                'label'=>Yii::t('store','Référence Commande Web'),
				'attribute' => 'order_id',
			],
            [
                'attribute'=>'document_id',
                'label'=>Yii::t('store','Order'),
	            'value' => function ($model, $key, $index, $widget) {
						return $model->document ? Html::a($model->document->name, Url::to(['/order/document/view', 'id' => $model->document_id])) : '';
				},
				'format' => 'raw',
            ],
			[
	            'label' => Yii::t('store', 'Created At'),
                'attribute'=>'created_at',
	            'value' => function ($model, $key, $index, $widget) {
						return new DateTime($model->created_at);
				},
				'format' => 'datetime',
            ],
	        [
	            'attribute' => 'status',
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	            'value' => function ($model, $key, $index, $widget) {
						return $model->getStatusLabel();
				},
	        ],
            [	// freely let update or delete if accessed throught this screen.
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {process}',
				'noWrap' => true,
				'buttons' => [
	                'process' => function ($url, $model) {
						$url = Url::to(['change-client', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-user"></i>', $url, [
	                        'title' => Yii::t('store', 'Change Client'),
	                    ]);
	                },
				],
			],

        ],
    ]); ?>

</div>