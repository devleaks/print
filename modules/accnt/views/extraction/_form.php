<?php

use app\models\Bill;
use app\models\Credit;
use app\models\Extraction;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use sjaakp\bandoneon\Bandoneon;

/* @var $this yii\web\View */
/* @var $model app\models\Extraction */
/* @var $form yii\widgets\ActiveForm */

if($model->extraction_type == '') $model->extraction_type = Extraction::TYPE_BILL;
if($model->extraction_method == '') $model->extraction_method = Extraction::METHOD_DATE;
$billsandcredits = ArrayHelper::map(Bill::find()->union(Credit::find())->asArray()->all(), 'id', 'name');
?>

<div class="extraction-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 2,
			'attributes' => [       // 2 column layout
				'extraction_type' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Extraction::getExtractionTypes(),
		            'columnOptions' => ['colspan' => 2],
					'options'=>['inline' => true],
				],
			],
		])
	?>
	<?= Html::activeHiddenInput($model, 'extraction_method') ?>

	<?php Bandoneon::begin() ?>

	<h4 class="method-option" data-method="REFN">
		<?= Yii::t('store', 'By Document Identifier') ?>
	</h4>

    <div>
	<?= Form::widget([
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
		])
	?>	
	</div>

	<h4 class="method-option" data-method="DATE">
		<?= Yii::t('store', 'By Document Date') ?>
	</h4>

    <div>
	<?= Form::widget([
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
		])
	?>
	</div>

	<?php Bandoneon::end() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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