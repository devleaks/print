<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('store', 'Create '.ucfirst(strtolower($model->order_type)));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', ucfirst(strtolower($model->order_type).'s')), 'url' => [strtolower($model->order_type).'s']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
		/** If create new doc, form is opened and closed here; there is a single form for the entire page */
		$form = ActiveForm::begin([
			'type'    => ActiveForm::TYPE_VERTICAL,
	        'options' => ['enctype' => 'multipart/form-data'],
		]); ?>

	<?= $this->render('_header_form', [
			'model' => $model,
			'form' => $form,
		])
	?>

	<?= $this->render('../order-line/_list_add', [
			'order' => $model,
			'form' => $form,
		])
	?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add Item'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
