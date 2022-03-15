<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Mailing List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'type'=>GridView::TYPE_PRIMARY,
	        'heading'=>$this->title,
	    ],
		'toolbar'=> [
	        '{export}',
	        '{toggleData}',
	    ],
        'columns' => [
            'email:email',
            'total:currency',
            [
            	'attribute' => 'last',
            	'format' => 'date',
            	'label' => Yii::t('store', 'Last buy')
            ],
            'notes'
        ],
    ]); ?>

</div>
