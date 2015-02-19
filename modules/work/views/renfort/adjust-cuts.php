<?php
use app\models\Item;
use app\models\Parameter;
use app\models\Task;
use app\models\Work;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cuts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this);

?>
<div class="work-line-index">

	<?php $form = ActiveForm::begin(['action' => Url::to(['prepare-cuts'])]); ?>

    <?= TabularForm::widget([
		'form' => $form,
        'dataProvider' => $dataProvider,
		'serialColumn' => false,
		'actionColumn' => false,
        'attributes' => [
	        'order_name' => [
				'type' => TabularForm::INPUT_RAW,
	            'label' => Yii::t('store', 'Support'),
	            'value' => function ($model, $key, $index, $widget) {
					if($model->documentLine->isChromaLuxe())
						$ret = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE])->libelle_long;
					else if($sup = $model->documentLine->getSupport())
						$ret = $sup->libelle_long;
					else
						$ret = Yii::t('store', 'None');
	                return Html::hiddenInput('WorkLineDetail['.$key.'][document_line_id]', $model->document_line_id)
							. $ret;
	            }
	        ],
	        'quantity' => [
				'type' => TabularForm::INPUT_RAW,
	            'label' => Yii::t('store', 'Qty'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->documentLine->quantity;
	            },
				'columnOptions'=>['hAlign'=>GridView::ALIGN_CENTER]
	        ],
	        'work_width' => [
				'type' => TabularForm::INPUT_RAW,
	            'label' => Yii::t('store', 'W-'),
	            'value' => function ($model, $key, $index, $widget) {
					return $model->documentLine->work_width;
	            },
				'columnOptions'=>['hAlign'=>GridView::ALIGN_CENTER]
	        ],
	        'work_height' => [
				'type' => TabularForm::INPUT_RAW,
	            'label' => Yii::t('store', 'H-'),
	            'value' => function ($model, $key, $index, $widget) {
					return $model->documentLine->work_height;
	            },
				'columnOptions'=>['hAlign'=>GridView::ALIGN_CENTER]
	        ],
	        'cut_width' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 200,
						'step' => 0.1,
						'decimals' => 1,
					],
					'class' => 'input-lg'
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'cut_width_count' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'decimals' => 0,
					]
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'margin_width' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 200,
						'step' => 0.1,
						'decimals' => 1,
					]
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'cut_height' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 200,
						'step' => 0.1,
						'decimals' => 1,
					]
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'cut_height_count' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'decimals' => 0,
					]
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'margin_height' => [
				'type' => TabularForm::INPUT_WIDGET,
				'widgetClass' => TouchSpin::classname(),
				'options' => [
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => 0,
						'max' => 200,
						'step' => 0.1,
						'decimals' => 1,
					]
				],
				'columnOptions'=>['vAlign'=>GridView::ALIGN_MIDDLE]
	        ],
	        'image' => [
				'type' => TabularForm::INPUT_RAW,
				'label' => Yii::t('store', 'Picture'),
	            'value' => function ($model, $key, $index, $widget) {
					$pic = $model->getDocumentLine()->one()->getPictures()->one();
					return $pic ? Html::img(Url::to($pic->getThumbnailUrl(), true)) : '';
					// placeholder: Yii::$app->homeUrl . 'assets/i/thumbnail.png';
                },
            	'format' => 'raw',
	        ],
       	],
    ]) ?>

	<?= Html::submitButton('<i class="glyphicon glyphicon-inbox"></i> '.Yii::t('store', 'Compute Cuts'), ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>