<?php

use app\models\DocumentLine;
use app\models\DocumentLineSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="document-line-print">

    <?= GridView::widget([
			'dataProvider' => $dataProvider,
			'layout' => '{items}',
			'condensed' => true,
			'columns' => [
				//['class' => 'kartik\grid\SerialColumn'],

				// 'id',
				// 'document_id',
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
					'attribute' => 'vat',
					'format' => 'percent',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->vat / 100;
				    },
					'noWrap' => true,
				],
				[
					'class' => '\kartik\grid\DataColumn',
					'attribute' => 'price_htva',
					'pageSummary' => true,
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
				],				
				[
					'class' => '\kartik\grid\DataColumn',
					'label' => Yii::t('store', 'Extra'),
					'attribute' => 'extra_htva',
				    'value' => function ($model, $key, $index, $widget) {
						return $model->extra_htva ? $model->extra_htva : '';
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
					'pageSummary' => true
				],
				[
					'label' => 'Prix HTVA',
				    'class' => '\kartik\grid\FormulaColumn',
				    'value' => function ($model, $key, $index, $widget) {
						$p = compact('model', 'key', 'index');
				        return round($widget->col(5, $p) + $widget->col(7, $p), 2);		
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
					'pageSummary' => true
				],				
				[
					'label' => 'TVA',
				    'class' => '\kartik\grid\FormulaColumn',
				    'value' => function ($model, $key, $index, $widget) {
						$p = compact('model', 'key', 'index');
				        return round( $widget->col(8, $p) * $widget->col(4, $p), 2);		
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
					'pageSummary' => true
				],				
				[
					'label' => 'Prix TVAC',
				    'class' => '\kartik\grid\FormulaColumn',
				    'value' => function ($model, $key, $index, $widget) {
						$p = compact('model', 'key', 'index');
				        return round( $widget->col(8, $p) + $widget->col(9, $p), 2);		
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'noWrap' => true,
					'pageSummary' => true
				],				

				//'note',
				//'work_width',
				//'work_height',
				// 'status',
				// 'created_at',
				// 'updated_at',
			],
		    'showPageSummary' => true,
		]);
	?>

</div>
