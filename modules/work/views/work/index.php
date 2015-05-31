<?php

use app\models\User;
use app\models\Work;
use kartik\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Url;

Icon::map($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Works');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        [
				'attribute' => 'order_name',
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
                    return User::hasRole(['manager', 'admin']) ? 
							Html::a($model->document->name, Url::to(['/order/document/view', 'id' => $model->document_id]))
							 : $model->document->name;
	            },
	            'format' => 'raw',
				'noWrap' => true,
	        ],
			[
				'attribute' => 'order_created_by',
                'label'=>Yii::t('store','Order Created By'),
	            'filter' => User::getList(),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->document->createdBy->username ;
				},
            	'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
            ],
	        [
				'attribute' => 'client_name',
				'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document->client->nom;
	            },
	            'format' => 'raw',
	        ],
			[
	            'label' => Yii::t('store', 'Due Date'),
                'attribute'=>'duedate_range',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->due_date;
				},
				'format' => 'date',
				'options' => ['class' => 'form-control'],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
	                'model'=>$searchModel,
	                'attribute'=>'due_date',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
					'initRangeExpr' => true,
					'presetDropdown' => false,
					'pluginOptions'=>[                                          
	                    'format'=>'Y-m-d',
	                    'opens'=>'left',
						'ranges' => [
						    Yii::t('store', "Today") => ["moment().startOf('day')", "moment()"],
					    	Yii::t('store', "This week") => ["moment().startOf('week')", "moment().endOf('week')"],
					    	Yii::t('store', "Next {0} days", 14) => ["moment()", "moment().add(14, 'day')"],
						    Yii::t('store', "This month") => ["moment().startOf('month')", "moment().endOf('month')"],
						    Yii::t('store', "Next month") => ["moment()", "moment().add(31, 'day')"],
						],
	                ],
					'pluginEvents' => [
						"apply.daterangepicker" => 'function() { $(".grid-view").yiiGridView("applyFilter"); }',
						"cancel.daterangepicker" => 'function() { $("#documentsearch-duedate_range").val(""); $(".grid-view").yiiGridView("applyFilter"); }',
					]
				]
            ],
	        [
	            'attribute' => 'status',
	            'filter' => Work::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
	        [
	            'label' => Yii::t('store', 'Tasks'),
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getTaskIcons(true, true, true);
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
            [	'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
	            'buttons' => [
	                'view' => function ($url, $model) {
						$url = Url::to(['work/view', 'id' => $model->id, 'sort' => 'position']);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
				],
			],
        ],
    ]); ?>

</div>
