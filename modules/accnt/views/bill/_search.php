<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BillSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'reference') ?>

    <?php // echo $form->field($model, 'reference_client') ?>

    <?php // echo $form->field($model, 'due_date') ?>

    <?php // echo $form->field($model, 'price_htva') ?>

    <?php // echo $form->field($model, 'price_tvac') ?>

    <?php // echo $form->field($model, 'vat') ?>

    <?php // echo $form->field($model, 'vat_bool') ?>

    <?php // echo $form->field($model, 'bom_bool') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'lang') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'document_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
