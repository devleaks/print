<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('store', 'Bank Slip Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'filename')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Upload'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
