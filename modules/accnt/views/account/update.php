<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = Yii::t('store', 'Update {modelClass} ', [
    'modelClass' => Yii::t('store', 'Account'),
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
