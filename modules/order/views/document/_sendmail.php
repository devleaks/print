<?php

use app\models\CaptureEmail;
use kartik\detail\DetailView;
use kartik\icons\Icon;
use kartik\widgets\SwitchInput;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;
//use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */

Icon::map($this);
$capture = new CaptureEmail();
$capture->id = $model->id;
$capture->email = $model->client->email;
?>

<?php Modal::begin([
    'header' => '<h2>'.Yii::t('store', 'Send by e-Mail').'</h2>',
    'toggleButton' => ['label' => '<i class="glyphicon glyphicon-envelope"></i> '.Yii::t('store', 'Send'), 'class' => 'btn btn-info', 'title' => Yii::t('store', 'Send')],
]) ?>

<?php if($capture->email == '') { ?>
	<div class="alert alert-warning" role="alert">
	<?= Yii::t('store', 'There is no email address associated with this client. Please provide one.') ?>
	</div>
<?php } ?>

<?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/send'])]); ?>
	<?= Html::activeHiddenInput($capture, 'id') ?>
	<?= $form->field($capture, 'email')->textInput() ?>
	<?= $form->field($capture, 'save')->widget(SwitchInput::className(),
		['pluginOptions' => ['onText' => Yii::t('store', 'Yes'), 'offText' =>  Yii::t('store', 'No')]
	]) ?>
	<?= $form->field($capture, 'body')->textarea(['rows' => 4]) ?>

	<div class="modal-footer our-modal-footer">
	<div class="form-group our-form-group">
		<?= Html::a('<i class="glyphicon glyphicon-remove"></i> '.Yii::t('store', 'Cancel'), '#', ['class' => 'btn btn-danger', 'data' => ['dismiss' => 'modal']]) ?>
		<?= Html::submitButton('<i class="glyphicon glyphicon-envelope"></i> '.Yii::t('store', 'Send'), ['class' => 'btn btn-primary']) ?>
	</div>
	</div>
<?php ActiveForm::end();

Modal::end();