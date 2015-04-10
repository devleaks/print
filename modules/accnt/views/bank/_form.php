<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BankTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'execution_date')->textInput() ?>

    <?= $form->field($model, 'value_date')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
