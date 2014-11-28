<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemTask */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Item Task',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="item-task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
