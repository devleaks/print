<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cash */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Cashes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?php if(!$model->sale): ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
		<?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'document_id',
            'sale',
            'amount',
            'payment_date',
            'note',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
