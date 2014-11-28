<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ParameterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'domain')->dropDownList(array_merge([""=>""] , ArrayHelper::map($model::find()->select('domain')->distinct()->asArray()->all(), 'domain', 'domain'))) ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'value_text') ?>

    <?= $form->field($model, 'value_number') ?>

    <?= $form->field($model, 'value_int') ?>

    <?php // echo $form->field($model, 'value_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
