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
        'method' => 'get',
		'layout' => 'horizontal',
    ]); ?>

        <div class="col-lg-4">
    <?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
					'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd'
		]]) ?>
        </div>

        <div class="col-lg-2">
    <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
