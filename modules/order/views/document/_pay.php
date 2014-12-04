<?php

use app\models\CapturePayment;
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

Icon::map($this);
$capture = new CapturePayment();
$capture->id = $model->id;
$capture->total = $model->price_tvac;
$capture->amount = $model->price_tvac - $model->prepaid;
$capture->method = 'BANKSYS';
$capture->submit = 1;
?>

<?php Modal::begin([
    'header' => '<h2>'.Yii::t('store', 'Pay').'</h2>',
    'toggleButton' => ['label' => '<span class="glyphicon glyphicon-shopping-cart"></span> '.Yii::t('store', 'Pay'), 'class' => 'btn btn-success', 'title' => Yii::t('store', 'Pay')],
]) ?>

<?= $this->render('_pay_previous', [
	'model' => $model,
])?>

<?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/pay'])]); ?>
	<?= Html::activeHiddenInput($capture, 'id') ?>
	<?= $form->field($capture, 'amount')->textInput() ?>
	<?= $form->field($capture, 'total')->textInput(['readonly' => true]) ?>
	<?= $form->field($capture, 'method')->dropDownList(Payment::getPaymentMethods()) ?>
	<?= $form->field($capture, 'submit')->widget(SwitchInput::className(),
		['pluginOptions' => ['onText' => Yii::t('store', 'Yes'), 'offText' =>  Yii::t('store', 'No')]
	]) ?>

	<div class="modal-footer our-modal-footer">
	<div class="form-group our-form-group">
		<?= Html::a('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('store', 'Cancel'), '#', ['class' => 'btn btn-danger', 'data' => ['dismiss' => 'modal']]) ?>
		<?= Html::submitButton('<span class="glyphicon glyphicon-shopping-cart"></span> '.Yii::t('store', 'Pay'), ['class' => 'btn btn-success']) ?>
	</div>
	</div>
<?php ActiveForm::end();

Modal::end();