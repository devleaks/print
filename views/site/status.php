<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = 'Labo JJ Micheli @Work';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome</h1>

<?php
	if(!$model) {
?>
        <p class="lead">Order not found.</p>
<?php
	} else {
?>
        <p class="lead">Order Status: Order Number <?= $model->name ?>.</p>

<p>
	Status here...
</p>
<?php
	}
?>


    <?php $form = ActiveForm::begin(['action' => 'status']); ?>

    <?= Html::textInput('id') ?>

<p></p>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Check Order Status'), ['class' =>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>
