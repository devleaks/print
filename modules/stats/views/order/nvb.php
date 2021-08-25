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

$this->title = Yii::t('store', 'Sales for NVB');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading'=>$this->title,
	    ],
		'toolbar'=> [
	        '{export}',
	        '{toggleData}',
	    ],
		'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
	        [
				'attribute' => 'document_type',
	            'label' => Yii::t('store', 'Type'),
				'filter' => Document::getDocumentTypes(),
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
	            'label' => Yii::t('store', 'NVB'),
                'attribute'=>'reference_interne',
				'options' => ['readonly' => true],
				'noWrap' => true,
	            'value' => function ($model, $key, $index, $widget) {
							return $model->client->reference_interne;
				}
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
				'format' => 'currency',
				'noWrap' => true,
				'pageSummary' => true,
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getAmount(false);
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
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
	                'model'=>$searchModel,
	                'attribute'=>'due_date',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
					'initRangeExpr' => true,
					'presetDropdown' => false,
					'pluginOptions'=>[                                          
	                    'locale'=>['format'=>'Y-m-d'],
	                    'opens'=>'left',
						'ranges' => [
						    Yii::t('store', "Today") => ["moment().startOf('day')", "moment()"],
					    	Yii::t('store', "This week") => ["moment().startOf('week')", "moment().endOf('week')"],
					    	Yii::t('store', "Next {0} days", 14) => ["moment()", "moment().add(14, 'day')"],
						    Yii::t('store', "This month") => ["moment().startOf('month')", "moment().endOf('month')"],
						    Yii::t('store', "This year") => ["moment().startOf('year')", "moment().endOf('year')"],
						],
	                ],
					'pluginEvents' => [
						"apply.daterangepicker" => 'function() { $(".grid-view").yiiGridView("applyFilter"); }',
						"cancel.daterangepicker" => 'function() { $("#documentsearch-duedate_range").val(""); $(".grid-view").yiiGridView("applyFilter"); }',
					]
				]
            ],
/*			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],*/
			[
	            'label' => Yii::t('store', 'Created At'),
                'attribute'=>'created_at_range',
	            'value' => function ($model, $key, $index, $widget) {
							return new DateTime($model->created_at);
				},
				'format' => 'datetime',
				'noWrap' => true,
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
	                    'locale'=>['format'=>'Y-m-d'], // 'format'=>'Y-m-d',
	                    'opens'=>'left',
						'ranges' => [
						    Yii::t('store', "Today") => ["moment().startOf('day')", "moment()"],
					    	Yii::t('store', "This week") => ["moment().startOf('week')", "moment().endOf('week')"],
					    	Yii::t('store', "Next {0} days", 14) => ["moment()", "moment().add(14, 'day')"],
						    Yii::t('store', "This month") => ["moment().startOf('month')", "moment().endOf('month')"],
						    Yii::t('store', "This year") => ["moment().startOf('year')", "moment().endOf('year')"],
						],
	                ],
					'pluginEvents' => [
						"apply.daterangepicker" => 'function() { $(".grid-view").yiiGridView("applyFilter"); }',
						"cancel.daterangepicker" => 'function() { $("#documentsearch-created_at_range").val(""); $(".grid-view").yiiGridView("applyFilter"); }',
					]
				]
            ],
	        [
	            'label' => Yii::t('store', 'Created By'),
				'attribute' => 'created_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
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
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
	                'model'=>$searchModel,
	                'attribute'=>'updated_at',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
	                'pluginOptions'=>[                                          
	                    'locale'=>['format'=>'Y-m-d'],
	                    'opens'=>'left',
						'ranges' => [
						    Yii::t('store', "Today") => ["moment().startOf('day')", "moment()"],
					    	Yii::t('store', "This week") => ["moment().startOf('week')", "moment().endOf('week')"],
					    	Yii::t('store', "Next {0} days", 14) => ["moment()", "moment().add(14, 'day')"],
						    Yii::t('store', "This month") => ["moment().startOf('month')", "moment().endOf('month')"],
						    Yii::t('store', "This year") => ["moment().startOf('year')", "moment().endOf('year')"],
						],
	                ],
					'pluginEvents' => [
						"apply.daterangepicker" => 'function() { $(".grid-view").yiiGridView("applyFilter"); }',
						"cancel.daterangepicker" => 'function() { $("#documentsearch-updated_at_range").val(""); $(".grid-view").yiiGridView("applyFilter"); }',
					]
				]
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