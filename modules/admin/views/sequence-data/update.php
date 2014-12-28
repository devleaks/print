<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SequenceData */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Sequence Data',
]) . ' ' . $model->sequence_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Sequence Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sequence_name, 'url' => ['view', 'id' => $model->sequence_name]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="sequence-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
