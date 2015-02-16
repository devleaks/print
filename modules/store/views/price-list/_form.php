<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if(! $model->sizes)
	$model->sizes = '20x30,24x30,30x30,30x40,30x45,40x40,40x50,40x60,50x60,50x70,50x75,60x80,60x90,75x75,70x100,75x100,75x112,80x120,100x100,100x125,100x150';
/* @var $this yii\web\View */
/* @var $model app\models\PriceList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="price-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'sizes')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
