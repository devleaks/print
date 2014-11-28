<?php

use app\models\Item;
use app\models\Option;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>
    <?= $form->field($model, 'label')->textInput(['maxlength' => 20]) ?>
    <?= $form->field($model, 'categorie')->dropDownList(
			ArrayHelper::map(Item::find()->select('categorie')->distinct()->orderBy('categorie')->asArray()->all(), 'categorie', 'categorie')
	) ?>
    <?= $form->field($model, 'option_type')->dropDownList(Option::getOptionTypes()) ?>
    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>
    <?= $form->field($model, 'status')->dropDownList(['ACTIVE' => Yii::t('store', 'ACTIVE'), 'INACTIVE' =>  Yii::t('store', 'INACTIVE')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
