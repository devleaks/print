<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Client */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Client',
]) . ' ' . $model->nom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nom, 'url' => ['view', 'id' => $model->id, 'reference_interne' => $model->reference_interne]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
