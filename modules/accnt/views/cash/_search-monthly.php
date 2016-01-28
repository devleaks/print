<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['monthly'],
        'method' => 'get',
		'layout' => 'horizontal',
    ]); ?>

    <div class="col-lg-4">
    <?= $form->field($model, 'payment_date')->widget(
			DatePicker::classname(), [
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm'
		]]) ?>
    </div>

    <div class="col-lg-1">
    <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
