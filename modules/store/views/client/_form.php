<?php

use app\models\Parameter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
	]); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 6,
			'attributes' => [
		        'titre' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Parameter::find()->where(['domain'=>'title'])->orderBy('value_int')->asArray()->all(), 'name', 'value_text'),
				],
		        'prenom' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'nom' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 3],
				],
		        'autre_nom' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'comptabilite' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'adresse' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 6],
				],
			]
		])
	?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 6,
			'attributes' => [
		        'code_postal' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'localite' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 3],
				],
		        'pays' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 1],
				],
		        'numero_tva' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 5],
				],
		        'lang' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Parameter::find()->where(['domain'=>'langue'])->orderBy('value_int')->asArray()->all(), 'name', 'value_text'),
		            'columnOptions' => ['colspan' => 1],
				],
			]
		])
	?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 6,
			'attributes' => [
		        'email' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 3],
				],
		        'site_web' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 3],
				],
		        'domicile' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'bureau' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'gsm' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 6,
			'attributes' => [
		        'fax_prive' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'fax_bureau' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'commentaires' => [
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 6],
				],
		        'comptabilite' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>
	
	<hr>

    <?= $form->field($model, 'reference_interne')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'pc')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'autre')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'remise')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'escompte')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'delais_de_paiement')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'mentions')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'exemplaires')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'limite_de_credit')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'formule')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'execution')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'support')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'format')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'mise_a_jour')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'mailing')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'outlook')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'categorie_de_client')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'operation')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'categorie_de_prix_de_vente')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'reference_1')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'date_limite_1')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'reference_2')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'date_limite_2')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'reference_3')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'date_limite_3')->textInput(['maxlength' => 80]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
