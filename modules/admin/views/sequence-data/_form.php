<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SequenceData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequence-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sequence_name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'sequence_increment')->textInput() ?>

    <?= $form->field($model, 'sequence_min_value')->textInput() ?>

    <?= $form->field($model, 'sequence_max_value')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'sequence_cur_value')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'sequence_cycle')->textInput() ?>

    <?= $form->field($model, 'sequence_year')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
