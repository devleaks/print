<?php

use app\models\CapturePayment;
use app\models\Document;
use app\models\Payment;
use kartik\icons\Icon;
use kartik\widgets\SwitchInput;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
$isPaid = $model->isPaid();
$label  = $isPaid ? Yii::t('store', 'Payments') : Yii::t('store', 'Pay');
Icon::map($this);
$capture = new CapturePayment();
$capture->id = $model->id;
$capture->total  = number_format($model->price_tvac, 2, ',', '');
$capture->amount = number_format($model->getBalance(), 2, ',', '');
$capture->method = 'BANKSYS';
$capture->submit = 1;
?>

<?php Modal::begin([
    'header' => '<h2>'.$label.'</h2>',
    'toggleButton' => [ 'label' => '<span class="glyphicon glyphicon-shopping-cart"></span> '.$label,
						'class' => 'btn btn-success', 'title' => $label],
]) ?>

<?= $this->render('_pay_previous', [
	'model' => $model,
])?>

<?php if(!$isPaid): ?>

	<?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/pay'])]); ?>
		<?= Html::activeHiddenInput($capture, 'id') ?>
		<?= $form->field($capture, 'amount')->textInput() ?>
		<?= $form->field($capture, 'total')->textInput(['readonly' => true]) ?>
		<?= $form->field($capture, 'method')->dropDownList(Payment::getPaymentMethods()) ?>
		<?php if(in_array($model->document_type, [Document::TYPE_ORDER, Document::TYPE_TICKET]) && !$model->getWorks()->exists() && !$model->getPayments()->exists()): ?>
		<?= $form->field($capture, 'submit')->widget(SwitchInput::className(),
			['pluginOptions' => ['onText' => Yii::t('store', 'Yes'), 'offText' =>  Yii::t('store', 'No')]
		]) ?>
		<?php endif; ?>

		<div class="modal-footer our-modal-footer">
		<div class="form-group our-form-group">
			<?= Html::a('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('store', 'Cancel'), '#', ['class' => 'btn btn-danger', 'data' => ['dismiss' => 'modal']]) ?>
			<?= Html::submitButton('<span class="glyphicon glyphicon-shopping-cart"></span> '.Yii::t('store', 'Pay'), ['class' => 'btn btn-success']) ?>
		</div>
		</div>
	<?php ActiveForm::end(); ?>

<?php endif; ?>

<?php Modal::end();