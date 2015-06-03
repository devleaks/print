<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentArchive */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Document Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-archive-view">

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
            // 'id',
            'document_type',
            'name',
            // 'sale',
            // 'reference',
            // 'reference_client',
            // 'parent_id',
            // 'client_id',
            // 'due_date',
            'price_htva',
            'price_tvac',
            // 'vat',
            // 'vat_bool',
            // 'bom_bool',
            // 'note',
            // 'lang',
            'status',
            'created_at',
            // 'created_by',
            'updated_at',
            // 'updated_by',
            // 'priority',
            // 'legal',
            // 'email:email',
            // 'credit_bool',
        ],
    ]) ?>

</div>
