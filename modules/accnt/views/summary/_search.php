<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\accnt\controllers\SummaryController;
/* @var $this yii\web\View */
/* @var $model app\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'id' => 'form-capture-date',
		'layout' => 'horizontal',
    ]); ?>

	<div class="col-lg-6">
	<?= $form->field($model, 'date')->widget(DatePicker::classname(), [
			'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
	]]) ?>
	<?= $form->field($model, 'action')->hiddenInput()->label('') ?>
	</div>

	<div class="col-lg-2">
		<?= Html::a('<i class="glyphicon glyphicon-search"></i> '.Yii::t('store', 'Search'), null, ['class' => 'btn btn-primary store-action', 'data-action' => SummaryController::ACTION_SEARCH]) ?>
	</div>
	<div class="col-lg-2">
		<?= Html::a('<i class="glyphicon glyphicon-print"></i> '.Yii::t('store', 'Print'), null, ['class' => 'btn btn-primary store-action', 'data-action' => SummaryController::ACTION_PRINT, 'target' => '_blank']) ?>
	</div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_ACTION') ?>
$("a.store-action").click(function(e) {
	$('#capturedate-action').val($(this).data('action'));
	$('#form-capture-date').submit();
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_ACTION'], yii\web\View::POS_READY);