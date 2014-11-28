<?php

use app\models\Document;
use app\models\Work;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */

$this->title = $model->getItem()->one()->libelle_long;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->order->order_type, true)), 'url' => ['order/'.strtolower($model->order->order_type).'s']];
$this->params['breadcrumbs'][] = ['label' => $model->getOrder()->one()->name, 'url' => Url::to(['/order/order-line/create', 'id' => $model->order_id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-line-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
	]); ?>

    <?= $this->render('_update', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
