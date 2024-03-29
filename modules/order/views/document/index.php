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
if(!isset($document_type)) {
	$document_type = 'doc';
	$button = '<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">'.
	        	Yii::t('store', 'Create '.ucfirst(strtolower($document_type))). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
				'<li>'.Html::a(Yii::t('store', 'Enter new bid'), ['create-bid'], ['title' => Yii::t('store', 'Enter new bid')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new order'), ['create'], ['title' => Yii::t('store', 'Enter new order')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new bill'), ['create-bill'], ['title' => Yii::t('store', 'Enter new bill')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new credit note'), ['create-credit'], ['title' => Yii::t('store', 'Enter new credit note')]).'</li>'.
			'</ul></div>';
} else
	$button = Html::a(Yii::t('store', 'Create '.ucfirst(strtolower($document_type))), ['create-'.strtolower($document_type)], ['class' => 'btn btn-success']);

Yii::$app->formatter->datetimeFormat = 'php:D j/n G:i';
	
$template = User::hasRole(['manager', 'admin', 'compta'])
	? '{view} {update} {change} {payment} {delete}'
	: '{view}';

$this->title = Yii::t('store', Document::getTypeLabel($document_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [User::hasRole(['manager', 'admin']) ? '/store' : '/order', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title).' '.$button ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'export' => [
		    'fontAwesome' => true,
		],
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Html::encode($this->title).'</h3>',
	        'before'=> $button,
	        'after'=> false, // Html::submitButton(Yii::t('store', 'Partial BOM'), ['class' => 'btn btn-primary']),
			'footer' => ' ',
	    ],
		'exportConfig' => [
   			GridView::PDF => [
				'config' => [
		            'mode' => 'c',
		            'format' => 'A4-L',
           			],
			],
			GridView::CSV => true
		],
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
	            'value' => function ($model, $key, $index, $widget) {
							return $model->name.($model->hasPicture()?'.':'');
				}
			],
            [
	            'label' => Yii::t('store', 'Communication'),
                'attribute'=>'reference',
				'options' => ['readonly' => true],
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
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
	                'model'=>$searchModel,
	                'attribute'=>'due_date',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
					'initRangeExpr' => true,
					'presetDropdown' => false,
					'pluginOptions'=>[
						'locale' => ['format'=>'Y-m-d'],
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
	                'attribute'=>'created_at',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
	                'pluginOptions'=>[                                          
						'locale' => ['format'=>'Y-m-d'],
	                    'opens'=>'left'
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
						'locale' => ['format'=>'Y-m-d'],
	                    'opens'=>'left'
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
//	        [
//	            'label' => Yii::t('store', 'Actions'),
//	            'value' => function ($model, $key, $index, $widget) {
//							return $model->getActions('btn btn-xs', false, '{icon}');
//	            		},
//				'hAlign' => GridView::ALIGN_CENTER,
//	            'format' => 'raw',
//				'noWrap' => true,
//				'options' => ['class' => 'IntroJS1'],
//	        ],
            [	// freely let update or delete if accessed throught this screen.
				'class' => 'kartik\grid\ActionColumn',
				'controller' => 'document',
			 	'template' => $template,
				'noWrap' => true,
				'buttons' => [
	                'change' => function ($url, $model) {
						$url = Url::to(['change-client', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-user"></i>', $url, [
	                        'title' => Yii::t('store', 'Change Client'),
	                    ]);
	                },
	                'payment' => function ($url, $model) {
						$url = Url::to(['/accnt/payment/sale', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-euro"></i>', $url, [
	                        'title' => Yii::t('store', 'View Payments'),
	                    ]);
	                },
				],
			],

        ],
    ]); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_INIT'); ?>
function addIntroJs(sid,intro) { $(sid).attr('data-intro', intro); }
addIntroJs('a[data-sort="name"]', "Tri pour cette colonne");
addIntroJs('table thead tr', "Champs de tri");
addIntroJs('input[name="DocumentSearch[name]"]', "Champ de recherche pour cette colonne");
addIntroJs('.filters', "Champs de sélection");
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);