<?php

use app\models\Item;
use app\models\ItemCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PriceListItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="price-list-item-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= Html::activeHiddenInput($model, 'price_list_id') ?>

    <?= $form->field($model, 'item_id')->dropDownList(ArrayHelper::map(Item::find()->where(['yii_category' => [
						ItemCategory::CHROMALUXE, ItemCategory::TIRAGE, ItemCategory::SUPPORT, ItemCategory::FRAME, ItemCategory::UV, ItemCategory::PROTECTION
						]])->asArray()->all(), 'id', 'libelle_long')) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
