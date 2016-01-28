<?php

use app\models\Payment;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="payment-form">
	
	<div class="alert alert-danger">
		Vous modifiez directement des paiements.
		Toute mauvaise manipulation peut entraîner des erreurs dans la comptabilité.
	</div>

    <?php $form = ActiveForm::begin(); ?>	

    <?= $form->field($model, 'note')->textInput() ?>

	<?php if($model->payment_method == Payment::CASH): ?>
		<p>
		<?= Yii::t('store', 'Payment Method: {0}. Cannot be changed here.', Yii::t('store', $model->payment_method)) ?>
		</p>
	<?php else: ?>
		<?= $form->field($model, 'payment_method')->dropDownList(Payment::getPaymentMethods(true)) ?>
	<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
