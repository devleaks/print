<?php

use app\models\OrderLine;
use app\models\OrderLineSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dataProvider->sort = false;
?>
<div class="order-line-print">

    <?= GridView::widget([
			'dataProvider' => $dataProvider,
			'layout' => '{items}',
			'condensed' => true,
			'striped' => false,
			'pageSummaryRowOptions' => ['class' => 'kv-page-summary'],
			'columns' => [
				//['class' => 'kartik\grid\SerialColumn'],

				// 'id',
				// 'order_id',
				// 'position',
				'item.reference',
				[
					'label' => Yii::t('store', 'Libellé'),
				    'class' => '\kartik\grid\DataColumn',
					'attribute' => 'item',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->getDescription();
				    },
				],				
				[
					'attribute' => 'quantity',
					'hAlign' => GridView::ALIGN_CENTER,
				],
				[
					'attribute' => 'unit_price',
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
				],
				[
					'label' => Yii::t('store', 'Extra'),
				    'class' => '\kartik\grid\DataColumn',
					'attribute' => 'extra_amount',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->getExtraDescription(false);
				    },
					'hAlign' => GridView::ALIGN_CENTER,
				],
				[
					'label' => Yii::t('store', 'Price Htva'),
				    'class' => '\kartik\grid\FormulaColumn',
				    'value' => function ($model, $key, $index, $widget) {
						$p = compact('model', 'key', 'index');
				        return round($model->price_htva + $model->extra_htva, 2);		
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
					'pageSummary' => true
				],				
				[
					'attribute' => 'vat',
					'format' => 'percent',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->vat / 100;
				    },
					'visible' => !$order->vat_bool,
					'noWrap' => true,
				],
//		        [
//					'class' => '\kartik\grid\DataColumn',
//					'label' => Yii::t('store', 'Picture'),
//					'attribute' => 'id',
//		            'value' => function ($model, $key, $index, $widget) {
//						$pic = $model->getPictures()->one();
//						return $pic ? Html::img(Url::to($pic->getThumbnailUrl(), true)) : '';
//						// placeholder: Yii::$app->homeUrl . 'assets/i/thumbnail.png';
//	                },
//					'visible' => $order->hasPicture(),
//					'hAlign' => GridView::ALIGN_CENTER,
//	            	'format' => 'raw',
//		        ],
			],
		    'showPageSummary' => true,
		]);
	?>

</div>
