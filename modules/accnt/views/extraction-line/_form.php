<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extraction-line-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'extraction_id')->textInput() ?>

    <?= $form->field($model, 'extraction_type')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
