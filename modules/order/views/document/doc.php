<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use app\models\User;
use app\widgets\GridViewPDF;
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

$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;
			
Yii::$app->formatter->datetimeFormat = 'php:D j/n G:i';

$this->title = Yii::t('store', Document::getTypeLabel($document_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [in_array($role, ['manager', 'admin']) ? '/store' : '/order', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

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
			]
		],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Référence'),
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
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Prepaid'),
				'format' => 'currency',
				'value' => function ($model, $key, $index, $widget) {
					return $model->getPrepaid();
				},
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
				'hAlign' => GridView::ALIGN_CENTER,
			],
/*			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'hAlign' => GridView::ALIGN_RIGHT,
			],*/
			[
                'attribute'=>'created_at',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
	                'model'=>$searchModel,
	                'attribute'=>'created_at',
	                'presetDropdown'=>TRUE,                
	                'convertFormat'=>true,                
	                'pluginOptions'=>[                                          
	                    'format'=>'Y-m-d',
	                    'opens'=>'left'
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
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				},
				'hAlign' => GridView::ALIGN_RIGHT,
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
//	        ],
	        [
				'class'	=> 'app\widgets\DocumentActionColumn',
				'noWrap' => true,
				'hAlign' => GridViewPDF::ALIGN_CENTER,
	        ],
        ],
    ]); ?>

</div>