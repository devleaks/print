<?php

use app\widgets\GridViewPDF as GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dataProvider->sort = false;
?>
<div class="document-line-print">

    <?= GridView::widget([
			'dataProvider' => $dataProvider,
			'summary' => false,
			'columns' => [
				[
					'label' => Yii::t('store', 'Ref.'),
					'attribute' => 'item.reference',
				],
				[
					'label' => Yii::t('store', 'Item'),
					'attribute' => 'item',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->getDescription();
				    },
					'format' => 'raw'
				],				
				[
					'label' => Yii::t('store', 'Qty'),
					'attribute' => 'quantity',
					'hAlign' => GridView::ALIGN_CENTER,
				],
				[
					'label' => Yii::t('store', 'Pc.'),
					'attribute' => 'unit_price',
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
				],
				[
					'label' => Yii::t('store', 'Extra'),
					'attribute' => 'extra_amount',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->getExtraDescription(false);
				    },
					'hAlign' => GridView::ALIGN_CENTER,
				],
				[
					'label' => Yii::t('store', 'Price Htva'),
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
