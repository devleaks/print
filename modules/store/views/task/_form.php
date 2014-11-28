<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use insolita\iconpicker\Iconpicker;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

	<?php if($model->icon) $model->icon = 'fa-'.$model->icon; ?>
    <?= $form->field($model, 'icon')->widget(Iconpicker::className(),[
			'rows' => 6,
			'columns' => 8,
			'iconset'=> 'fontawesome'
	])->label(Yii::t('store', 'Choose icon')) ?>

    <?= $form->field($model, 'first_run')->textInput() ?>

    <?= $form->field($model, 'next_run')->textInput() ?>

    <?= $form->field($model, 'unit_cost')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList($model::getStatuses()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
