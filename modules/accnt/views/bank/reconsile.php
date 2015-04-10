<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */

$this->title = Yii::t('store', 'Bank Slip Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
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
            'bill',
            'extract_amount',
            'bill_amount',
        ],
    ]); ?>
</div>
