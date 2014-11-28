<?php

use app\models\Extraction;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Extraction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extraction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'extraction_type')->dropDownList(Extraction::getExtractionTypes()) ?>
    <?= $form->field($model, 'date_from')->widget(DatePicker::classname(),[
							'pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            ],]) ?>
    <?= $form->field($model, 'date_to')->widget(DatePicker::classname(),[
							'pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            ],]) ?>
    <?= $form->field($model, 'order_from')->textInput() ?>
    <?= $form->field($model, 'order_to')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
