<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Document;

/* @var $this yii\web\View */

$this->title = Yii::t('store', 'Reconsile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Bank Slip Upload'), 'url' => ['/accnt/bank']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reconsile-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'rowOptions' => function ($model, $index, $widget, $grid){
	      	return ['class'=> ($model['bill_amount'] == $model['extract_amount'] ? 'success' : 'warning')];
	    },
        'columns' => [
            'code',
            'extract',
            'extract_amount',
            'bill_amount',
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
        ],
    ]); ?>
</div>
