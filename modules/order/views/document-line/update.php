<?php

use app\models\Document;
use app\models\Work;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */

$this->title = $model->getItem()->one()->libelle_long;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->document->document_type, true)), 'url' => ['document/'.strtolower($model->document->document_type).'s']];
$this->params['breadcrumbs'][] = ['label' => $model->getDocument()->one()->name, 'url' => Url::to(['/order/document-line/create', 'id' => $model->document_id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-line-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
		'id' => 'documentline-form',
	]); ?>

    <?= $this->render('_update', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
