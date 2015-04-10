<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BankTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'execution_date') ?>

    <?= $form->field($model, 'value_date') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'account') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
