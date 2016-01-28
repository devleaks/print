<?php

use app\models\Payment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'payment_method')->dropDownList(Payment::getPaymentMethods()) ?>

    <?= $form->field($model, 'payment_date')->widget(DateTimePicker::className(), [
			'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true
            	],
				'options' => ['data-intro' => "Vous devez mentionner une date de livraison pour la commande. Si la date de livraison n'a pas d'importance, entrez la date du jour."],
	]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
