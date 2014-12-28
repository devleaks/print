<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SequenceDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequence-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sequence_name') ?>

    <?= $form->field($model, 'sequence_increment') ?>

    <?= $form->field($model, 'sequence_min_value') ?>

    <?= $form->field($model, 'sequence_max_value') ?>

    <?= $form->field($model, 'sequence_cur_value') ?>

    <?php // echo $form->field($model, 'sequence_cycle') ?>

    <?php // echo $form->field($model, 'sequence_year') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
