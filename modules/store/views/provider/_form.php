<?php

use app\models\Item;
use app\models\ItemCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Provider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provider-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->dropDownList([
		'item' => ArrayHelper::map(Item::find()->select('fournisseur')->where(['yii_category'=>ItemCategory::FRAME])->orderBy('fournisseur')->asArray()->all(), 'fournisseur', 'fournisseur')
	]) ?>


    <?= $form->field($model, 'email')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'status')->widget(SwitchInput::className(),
					['pluginOptions' => [
								'onText' => Yii::t('store', 'Active'),
								'offText' =>  Yii::t('store', 'Inactive'),
						        'onColor' => 'success',
						        'offColor' => 'danger',
								'state' => $model->status == 'ACTIVE'
					]]
	) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
