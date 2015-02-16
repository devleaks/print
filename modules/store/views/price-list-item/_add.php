<?php

use app\models\Item;
use app\models\ItemCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-item-form">

	<?php $form = ActiveForm::begin([
			'action' => Url::to(['price-list-item/add'])
          ]);
	?>

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'price_list_id') ?>

    <?= $form->field($model, 'item_id')->dropDownList(ArrayHelper::map(Item::find()->where(['yii_category' => [
						ItemCategory::CHROMALUXE,
						ItemCategory::TIRAGE,
						ItemCategory::SUPPORT,
						ItemCategory::MONTAGE,
						ItemCategory::RENFORT,
						ItemCategory::FRAME,
						ItemCategory::UV,
						ItemCategory::PROTECTION
						]])
						->andWhere(['status' => Item::STATUS_ACTIVE])->orderBy('yii_category,libelle_long')->asArray()->all(), 'id', 'libelle_long')) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add Item'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
