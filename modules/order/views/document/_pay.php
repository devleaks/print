<?php

use app\models\CapturePayment;
use app\models\Document;
use app\models\Payment;
use app\models\History;
use app\models\User;
use kartik\icons\Icon;
use kartik\widgets\SwitchInput;
use yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;
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
$capture->total  = number_format($model->getTotal()  , 2, ',', '');
$capture->amount = number_format($model->getBalance(), 2, ',', '');
//$capture->method = Payment::CASH;
$capture->use_credit = false;
$capture->submit = 1;

$capture->click = uniqid();
$_SESSION['captureclick'] = $capture->click;
History::record($capture, 'SET', $capture->click, false, null);

?>

<?php Modal::begin([
    'header' => '<h2>'.$label.'</h2>',
    'toggleButton' => [ 'label' => '<i class="glyphicon glyphicon-shopping-cart"></i> '.$label,
						'class' => 'btn btn-success', 'title' => $label],
]) ?>

<?= $this->render('_pay_previous', [
	'model' => $model,
])?>

<?php if(!$isPaid): ?>

	<?php 	$credit_lines = [];
			if(!in_array($model->document_type, [$model::TYPE_CREDIT])) {
				Yii::trace('Getting payments', 'Client::getAccountLines');
				$exclude = ($model->document_type == $model::TYPE_REFUND) ? $model->id : null;
				$credit_lines = $model->client->getCreditLines($exclude);
				echo $this->render('_available_credit', [
					'dataProvider' => new ArrayDataProvider([
						'allModels' => $credit_lines,
						'pagination' => false,
					]),
				]);
			}
			$payment_methods = Payment::getPaymentMethods();
			if(count($credit_lines) > 0) {// add credit option if any
				$payment_methods[Payment::USE_CREDIT] = Yii::t('store', 'Use Credit');
			}
	?>

	<?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/pay'])]); ?>
		<?= Html::activeHiddenInput($capture, 'id') ?>
		<?= Html::activeHiddenInput($capture, 'click') ?>
		<?php /** Experimental */
			if(defined('YII_DEVLEAKS') && in_array($model->document_type, [Document::TYPE_REFUND])) {
				echo $form->field($capture, 'use_credit')->checkbox();
			}
		?>
		<?= $form->field($capture, 'amount')->textInput() ?>
		<?= $form->field($capture, 'note')->textInput() ?>
		<?= $form->field($capture, 'total')->textInput(['readonly' => true]) ?>
		<?= $form->field($capture, 'method')->dropDownList($payment_methods) ?>
		<?php
			if(!User::hasRole(['compta'])
				&& in_array($model->document_type, [Document::TYPE_ORDER, Document::TYPE_TICKET])
				&& !$model->getWorks()->exists()
				&& !$model->getPayments()->exists()) {
				echo $form->field($capture, 'submit')->widget(SwitchInput::className(),
					['pluginOptions' => ['onText' => Yii::t('store', 'Yes'), 'offText' =>  Yii::t('store', 'No')]
				]);
			}
		?>

		<div class="modal-footer our-modal-footer">
		<div class="form-group our-form-group">
			<?= Html::a('<i class="glyphicon glyphicon-remove"></i> '.Yii::t('store', 'Cancel'), '#', ['class' => 'btn btn-danger', 'data' => ['dismiss' => 'modal']]) ?>
			<?= Html::submitButton('<i class="glyphicon glyphicon-shopping-cart"></i> '.Yii::t('store', 'Pay'), ['class' => 'btn btn-success']) ?>
		</div>
		</div>
	<?php ActiveForm::end(); ?>

<?php endif; ?>
<script type="text/javascript">
<?php
$this->beginBlock('JS_CREDIT') ?>
$('#capturepayment-use_credit').change(function() {
	if($(this).prop('checked')) {
		$('#capturepayment-amount').val($('#capturepayment-total').val()).prop('readonly', true);;
	} else
		$('#capturepayment-amount').prop('readonly', false);;
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_CREDIT'], yii\web\View::POS_READY);

Modal::end();
