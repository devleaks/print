<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bill */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'parent_id',
            'client_id',
            'name',
            'reference',
            'reference_client',
            'due_date',
            'price_htva',
            'price_tvac',
            'vat',
            'vat_bool',
            'bom_bool',
            'note',
            'lang',
            'status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'document_type',
        ],
    ]) ?>

</div>
