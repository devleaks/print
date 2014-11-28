<?php

use app\models\Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('store', 'Create '.Document::getTypeLabel($model->order_type));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->order_type, true)), 'url' => ['order/'.strtolower($model->order_type).'s']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('../order/_header_view', [
        'model' => $model,
    ]) ?>

    <?= $this->render('_list_add', [
		'order' => $model,
        'orderLine' => $orderLine,
		'form'	=> null /** if adding to existing doc, form is opened in _add */
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add Item'), ['class' => 'btn btn-primary', 'id' => 'orderlinedetail-submit']) ?>
    </div>

    <?php 	/** if adding to existing doc, form is opened in _add */
		ActiveForm::end();
	?>

</div>
