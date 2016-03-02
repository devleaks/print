<?php

use app\models\Document;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$limit = 6;
$dataProvider = new ActiveDataProvider([
	'query' => Document::find()->orderBy('created_at desc')->limit($limit),
	'sort' => false,
]);
$dataProvider->pagination->pageSize = $limit;
?>
<div class="recent-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'summary' => false,
		'sorter' => false,
		'layout' => '{items}',
        'columns' => [
	        [
				'attribute' => 'document_type',
	            'label' => Yii::t('store', 'Type'),
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->document_type);
				}
			],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Référence'),
				'noWrap' => true,
			],
	        [
				'attribute' => 'client_name',
	            'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->client->nom;
				}
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'price_tvac',
				'hAlign' => GridView::ALIGN_RIGHT,
				'format' => 'raw',
				'noWrap' => true,
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getAmount(true);
				},
			],
			[
	            'label' => Yii::t('store', 'Due Date'),
                'attribute'=>'duedate_range',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->due_date;
				},
				'format' => 'date',
				'options' => ['class' => 'form-control'],
            ],
			[
	            'label' => Yii::t('store', 'Created At'),
                'attribute'=>'created_at_range',
	            'value' => function ($model, $key, $index, $widget) {
							return new DateTime($model->created_at);
				},
				'format' => 'datetime',
				'noWrap' => true,
            ],
	        [
	            'label' => Yii::t('store', 'Created By'),
				'attribute' => 'created_by',
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getCreatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],
			[
	            'label' => Yii::t('store', 'Updated At'),
                'attribute'=>'updated_at_range',
	            'value' => function ($model, $key, $index, $widget) {
						return new DateTime($model->updated_at);
				},
				'options' => ['class' => 'form-control'],
				'format' => 'datetime',
				'noWrap' => true,
            ],
	        [
	            'label' => Yii::t('store', 'Status'),
	            'attribute' => 'status',
	            'filter' => Document::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getStatusLabel(true);
	            		},
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],

        ],
    ]); ?>

</div>