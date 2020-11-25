<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Jo and Z srl @Work';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome</h1>

        <p class="lead">Check Order Status.</p>

    <?php $form = ActiveForm::begin(['action' => 'site/status']); ?>
    <?= Html::textInput('id') ?>

<p></p>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Check Order Status'), ['class' =>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>
