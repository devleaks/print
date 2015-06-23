<?php

use app\models\Document;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('store', 'Added Payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Bank Slip Upload'), 'url' => ['/accnt/bank']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reconsile-form">

    <?= GridView::widget([
		'options' => ['id' => 'action-gridview'],
        'dataProvider' => $dataProvider,
		'rowOptions' => function ($model, $index, $widget, $grid){
	      	return ['class'=> ($model['bill_amount'] == $model['extract_amount'] ? 'success' : 'warning')];
	    },
		'panel' => [
	        'heading'=> Html::tag('h4', $this->title, ['class' => "panel-title"]),
	        'before'=> false,
	        'after'=> Html::submitButton('<i class="glyphicon glyphicon-euro"></i> '.Yii::t('store', 'Make Payments'),
							['class' => 'btn btn-primary'])
				,
	        'showFooter'=>false
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
	        [
				'attribute' => 'order_name',
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
					if($model['bill'])
						if($doc = Document::findOne(['name' => $model['bill']]))
	                    	return Html::a($doc->name, Url::to(['/order/document/view', 'id' => $doc->id]), ['target' => '_blank']);
					return $model['bill'];
	            },
	            'format' => 'raw',
				'noWrap' => true,
	        ],
	        [
				'attribute' => 'code',
				'label' => Yii::t('store', 'Structured Communication'),
			],
	        [
				'attribute' => 'bill_amount',
				'format' => 'currency',
				'label' => Yii::t('store', 'Order Amount'),
			],
	        [
				'attribute' => 'bill_due',
				'format' => 'currency',
				'label' => Yii::t('store', 'Amount Due'),
			],
	        [
				'attribute' => 'extract',
				'label' => Yii::t('store', 'Slip Number'),
			],
	        [
				'attribute' => 'extract_amount',
				'format' => 'currency',
				'label' => Yii::t('store', 'Slip Amount'),
			],
	        [
				'attribute' => 'extract_status',
				'label' => Yii::t('store', 'Slip Status'),
			],
        ],
    ]); ?>

</div>