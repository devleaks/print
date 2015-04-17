<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SequenceData */

$this->title = $model->sequence_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Sequence Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->sequence_name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->sequence_name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sequence_name',
            'sequence_increment',
            'sequence_min_value',
            'sequence_max_value',
            'sequence_cur_value',
            'sequence_cycle',
            'sequence_year',
        ],
    ]) ?>

</div>
