<?php

use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
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
			'columns' => 6,
			'attributes' => [
		        'reference' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'libelle_court' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'libelle_long' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 6],
				],
		        'categorie' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_de_vente' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 1],
				],
		        'taux_de_tva' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 1],
				],
		        'status' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => $model::getStatuses(),
		            'columnOptions' => ['colspan' => 1],
				],
		        'commentaires' => [
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 6],
				],
		        'yii_category' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ItemCategory::getCategories(),
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
