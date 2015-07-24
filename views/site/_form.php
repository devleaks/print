<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CaptureSearch */
?>

<div class="col-lg-offset-2 col-lg-8 search-form">

    <?php $form = ActiveForm::begin(['action' => Url::to(['/order/document/search'])]); ?>

    <?= $form->field($model, 'search', [
	    'addon' => [
			'append' => [
				'content' => '<i class="glyphicon glyphicon-search"></i>',
			]
		]
	])->textInput(['maxlength' => 40, 'class' => 'input-lg'])->label('')
	?>

    <?php ActiveForm::end(); ?>

</div>
