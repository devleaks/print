<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CaptureSearch */
?>

<div class="search-form">

    <?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/search'])]); ?>

    <?= $form->field($model, 'search')->textInput(['maxlength' => 40, 'class' => 'input-lg'])->label(
			Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-default'])
		)
	?>

    <?php ActiveForm::end(); ?>

</div>
