<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bill-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'client_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'reference')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'reference_client')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'due_date')->textInput() ?>

    <?= $form->field($model, 'price_htva')->textInput() ?>

    <?= $form->field($model, 'price_tvac')->textInput() ?>

    <?= $form->field($model, 'prepaid')->textInput() ?>

    <?= $form->field($model, 'paiement_method')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'vat')->textInput() ?>

    <?= $form->field($model, 'vat_bool')->textInput() ?>

    <?= $form->field($model, 'bom_bool')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'lang')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'document_type')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
