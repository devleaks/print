<?php

use app\models\Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = Yii::t('store', 'Create '.ucfirst(strtolower($model->document_type)));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->document_type, true)), 'url' => ['/order/document/'.strtolower($model->document_type).'s', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('../document/_header_view', [
        'model' => $model,
    ]) ?>

    <?= $this->render('_list_add', [
		'order' => $model,
        'orderLine' => $orderLine,
		'form'	=> null /** if adding to existing doc, form is opened in _add */
    ]) ?>

</div>
