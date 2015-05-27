<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
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
		<?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> '.Yii::t('store', 'Search'), ['class' => 'btn btn-primary store-action']) ?>
	</div>
	<div class="col-lg-2">
		<?= Html::a('<i class="glyphicon glyphicon-print"></i> '.Yii::t('store', 'Print'), Url::to(['print', 'd' => $model->date]), ['class' => 'btn btn-primary store-action', 'target' => '_blank']) ?>
	</div>

    <?php ActiveForm::end(); ?>

</div>