<?php

use app\models\Extraction;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Extraction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extraction-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 3,
			'attributes' => [       // 2 column layout
				'extraction_type' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Extraction::getExtractionTypes(),
		            'columnOptions' => ['colspan' => 3],
					'options'=>['inline' => true],
				],
		        'document_from' => [
					'type' => Form::INPUT_TEXT,
				],
		        'date_from' => [
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
	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 3,
			'attributes' => [       // 2 column layout
		        'document_to' => [
					'type' => Form::INPUT_TEXT,
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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
