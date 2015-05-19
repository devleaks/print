<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parameter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Parameters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->domain, 'url' => ['index', 'ParameterSearch' => ['domain'=>$model->domain]]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'domain' => $model->domain, 'name' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'domain' => $model->domain, 'name' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'domain',
            'name',
            'lang',
            'value_text',
            'value_number',
            'value_int',
            'value_date',
        ],
    ]) ?>

</div>
