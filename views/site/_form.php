<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-form">

    <?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/search'])]); ?>

    <?= Html::textInput('search', null, ['maxlength' => 40, 'class' => 'input-lg']) ?>

    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-lg btn-default']) ?>

    <?php ActiveForm::end(); ?>

</div>
