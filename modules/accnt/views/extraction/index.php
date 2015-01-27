<?php

use app\models\Bill;
use app\models\Credit;
use app\models\CaptureExtraction;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Extraction */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('store', 'Create Extraction');

if($model->extraction_type == '') $model->extraction_type = CaptureExtraction::TYPE_BILL;
if($model->extraction_method == '') $model->extraction_method = CaptureExtraction::METHOD_DATE;

$billsandcredits = ArrayHelper::map(Bill::find()->union(Credit::find())->asArray()->all(), 'id', 'name');

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Extractions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="extraction-form">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		
	<div class="col-lg-4">
	<?= $form->field($model, 'extraction_type')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
        		'size' => 'large',
				'onText' => Yii::t('store', CaptureExtraction::TYPE_BILL),
				'offText' =>  Yii::t('store', CaptureExtraction::TYPE_CREDIT),
				'onColor' => 'success',
				'offColor' => 'primary',
				'state' => ($model->extraction_type == CaptureExtraction::TYPE_BILL)
	]]) ?>
	</div>
	
	<div class="col-lg-8">
	<?= Tabs::widget([
		'items' => [
			[
				'label' => Yii::t('store', 'By Date'),
				'active' => $model->extraction_method == CaptureExtraction::METHOD_DATE,
				'content' => Form::widget([
					'model' => $model,
					'form' => $form,
					'columns' => 2,
					'attributes' => [       // 2 column layout
				        'date_from' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> DatePicker::classname(),
							'options' => ['pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            ]],
						],
				        'date_to' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> DatePicker::classname(),
							'options' => ['pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            ]],
						],
					],
				]),
				'headerOptions' => ['class' => 'method-option', 'data-method' => CaptureExtraction::METHOD_REFN]
			],
			[
				'label' => Yii::t('store', 'By Number'),
				'active' => $model->extraction_method == CaptureExtraction::METHOD_REFN,
				'content' => Form::widget([
					'model' => $model,
					'form' => $form,
					'columns' => 2,
					'attributes' => [       // 2 column layout
				        'document_from' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => $billsandcredits,
						],
				        'document_to' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => $billsandcredits,
						],
					],
				]),
				'headerOptions' => ['class' => 'method-option', 'data-method' => CaptureExtraction::METHOD_DATE]
			]
		]
	]) ?>		
	</div>

	</div>

	<?= Html::activeHiddenInput($model, 'extraction_method') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Generate'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_EXTRACT_METHOD'); ?>
$(".method-option").click(function () {
	method = $(this).data('method');
	$('#extraction-extraction_method').val(method);
});
<?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['JS_EXTRACT_METHOD'], yii\web\View::POS_END);