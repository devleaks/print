<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Parameter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'value_text')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'value_number')->textInput() ?>

    <?= $form->field($model, 'value_int')->textInput() ?>

    <?= $form->field($model, 'value_date')->widget(DatePicker::classname(),[
		'pluginOptions' => [
               'format' => 'yyyy-mm-dd',
               'todayHighlight' => true
           ]]
	) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
