<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-form">

    <?php $form = ActiveForm::begin(['action' => Url::to(['/order/order/search'])]); ?>

    <?= Html::textInput('search', null, ['maxlength' => 40, 'class' => 'input-lg']) ?>

    <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-info']) ?>

    <?php ActiveForm::end(); ?>

</div>
