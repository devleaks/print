<?php

use app\models\Document;
use app\models\DocumentArchive;
use kartik\date\DatePicker;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentArchive */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-archive-form">
	
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'document_type')->dropDownList([
		Document::TYPE_ORDER => Yii::t('store', Document::TYPE_ORDER),
		Document::TYPE_TICKET => Yii::t('store', Document::TYPE_TICKET),
	]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'due_date')->widget(DatePicker::classname(), [
			'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
	]])->label(Yii::t('store', 'Date')) ?>

    <?= $form->field($model, 'price_htva_virgule')->textInput(['maxlength' => true])->label(Yii::t('store', 'Price HTVA')) ?>

    <?= $form->field($model, 'price_tvac_virgule')->textInput(['maxlength' => true])->label(Yii::t('store', 'Price TVAC')) ?>

    <?= $form->field($model, 'status')->widget(SwitchInput::className(),
					['pluginOptions' => [
								'onText' => Yii::t('store', 'Active'),
								'offText' =>  Yii::t('store', 'Inactive'),
						        'onColor' => 'success',
						        'offColor' => 'danger',
								'state' => ($model->status == DocumentArchive::STATUS_ACTIVE)
					]]
	) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
