<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($order_type))
	$order_type = 'doc';

$this->title = Yii::t('store', Document::getTypeLabel($order_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?>
        <?= $order_type == Document::TYPE_BOM ? '' : Html::a(Yii::t('store', 'Create '.Document::getTypeLabel($order_type)), ['create-'.strtolower($order_type)],
			['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
				'attribute' => 'price_htva',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
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
	        [
	            'label' => Yii::t('store', 'Actions'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getActions('btn btn-xs', false, '{icon}');
	            		},
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
				'noWrap' => true,
	        ],
        ],
    ]); ?>

</div>