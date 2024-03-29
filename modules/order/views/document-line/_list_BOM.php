<?php

use app\models\DocumentLine;
use app\models\DocumentLineSearch;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/*
$contentBefore = $this->render('../document/_header_print', ['model' => $order]);
$contentAfter = $this->render('../document/_footer_print', ['model' => $order]);
*/
?>
<div class="document-line-list">
<p></p>

	<?php $form = ActiveForm::begin(['action' => Url::to(['document/bom', 'id' => $order->id])]) ?>

    <?= GridView::widget([
			'dataProvider' => $dataProvider,
			//'filterModel' => $searchModel,
/*			'export' => [
			    'fontAwesome' => true,
			],
			'exportConfig' => [
    			GridView::PDF => [
					'config' => [
			            'mode' => 'c',
			            'format' => 'A4',
	        			'contentBefore'=> $contentBefore ,
						'contentAfter'=> $contentAfter,
						'methods' => [
			                'SetHeader' => [],
			                'SetFooter' => [$contentAfter],
            			],
					'options' => [],
					]
			 	]
			],*/
			'toolbar' => false,
			'panel' => [
		        'heading'=> '<h3 class="panel-title">'.Yii::t('store', 'Items').'</h3>',
		        'before'=> ' ',
		        'after'=> Html::submitButton(Yii::t('store', 'Partial BOM'), ['class' => 'btn btn-primary']),
		    ],
			'columns' => [
				['class' => 'kartik\grid\SerialColumn'],
				//'item.reference',
				'item.libelle_court',
				[
					'attribute' => 'work_width',
					'hAlign' => GridView::ALIGN_CENTER,
				],
				[
					'attribute' => 'work_height',
					'hAlign' => GridView::ALIGN_CENTER,
				],
				'note',
				[
					'attribute' => 'due_date',
					'format' => 'date',
					'hAlign' => GridView::ALIGN_CENTER,
					'noWrap' => true,
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
					'visible' => !$order->vat_bool,
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
					'noWrap' => true,
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
				        return round( floatval($widget->col(9, $p)) + floatval($widget->col(11, $p)), 2);		
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
				        return $model->document->vat_bool ? '0' : round( floatval($widget->col(12, $p)) * ( 1 + floatval($widget->col(8, $p)) , 2); // already /100 on col. 7.
				    },
					'format' => 'currency',
					'hAlign' => GridView::ALIGN_RIGHT,
					'visible' => !$order->vat_bool,
					'noWrap' => true,
					'pageSummary' => true
				],				
		        [
					'class' => '\kartik\grid\DataColumn',
					'label' => Yii::t('store', 'Picture'),
					'attribute' => 'id',
		            'value' => function ($model, $key, $index, $widget) {
						$pic = $model->getPictures()->one();
						return $pic ? Html::img(Url::to($pic->getThumbnailUrl(), true)) : '';
						// placeholder: Yii::$app->homeUrl . 'assets/i/thumbnail.png';
	                },
					'hAlign' => GridView::ALIGN_CENTER,
					'visible' => $order->hasPicture(),
	            	'format' => 'raw',
		        ],
				[
					'class' => 'kartik\grid\ActionColumn',
					'controller' => 'document-line',
					'template' => $action_template,
				],
				[
					'class' => 'kartik\grid\CheckboxColumn',
				]
			],
		    'showPageSummary' => true,
		]);
	?>

    <?php ActiveForm::end(); ?>

</div>
