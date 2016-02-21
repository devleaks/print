<?php

use app\models\AccountingJournal;
use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
	]); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 12,
			'attributes' => [
		        'reference' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'libelle_long' => [
					'label' => Html::label(Yii::t('store', 'Label')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 8],
				],
		        'libelle_court' => [
					'label' => Html::label(Yii::t('store', 'Short Label')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'categorie' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_de_vente' => [
					'label' => Html::label(Yii::t('store', 'Prix')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'taux_de_tva' => [
					'label' => Html::label(Yii::t('store', 'VAT')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 1],
				],
		        'commentaires' => [
					'label' => Html::label(Yii::t('store', 'Notes')),
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 12],
				],
		        'comptabilite' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(AccountingJournal::find()->asArray()->all(), 'code', 'name'),
		            'columnOptions' => ['colspan' => 2],
				],
		        'yii_category' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ItemCategory::getCategories(),
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_a' => [
					'label' => Html::label(Yii::t('store', 'Prix « A &times <i>x</i> »')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_b' => [
					'label' => Html::label(Yii::t('store', 'Prix « + B »')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'status' => [
					'label' => Html::label(Yii::t('store', 'Status')).'<br>',
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=>SwitchInput::className(),
					'options' => ['pluginOptions' => [
								'onText' => Yii::t('store', $model::STATUS_ACTIVE),
								'offText' =>  Yii::t('store', $model::STATUS_RETIRED),
						        'onColor' => 'success',
						        'offColor' => 'danger',
								'state' => $model->status == $model::STATUS_ACTIVE
					]],
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Add') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
