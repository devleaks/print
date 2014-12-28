<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
$pending_bills = $client->getDocuments()
						->andWhere(['status' => Document::STATUS_TOPAY]);
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= Html::activeHiddenInput($model, 'client_id') ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'status')->dropDownList(['item' => array_merge(['Multiple' => 'Multiple'], $pending_bills]) ?>

    <?= $form->field($model, 'status')->dropDownList(['item' => $model::getStatuses()]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
