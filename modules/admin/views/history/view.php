<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\History */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
            'object_type:ntext',
            'object_id',
            'action',
            'summary',
            'note',
            'performer_id',
            'created_at',
            [
				'attribute' => 'payload',
				'value' => VarDumper::dumpAsString(Json::decode($model->payload), 4, true),
				'format' => 'raw',
			]
        ],
    ]) ?>

</div>
