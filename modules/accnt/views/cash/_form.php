<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cash */
/* @var $form yii\widgets\ActiveForm */
$model->amount_virgule = str_replace('.',',',$model->amount);
$model->mode = $model->amount > 0 ? $model::CREDIT : $model::DEBIT;
?>

<div class="cash-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount_virgule')->textInput() ?>

	<?= $form->field($model, 'mode')->dropDownList([$model::DEBIT => Yii::t('store', $model::DEBIT), $model::CREDIT => Yii::t('store', $model::CREDIT)]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
