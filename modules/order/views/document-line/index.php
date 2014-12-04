<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Order Lines');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-line-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Order Line'), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'document_id',
            'position',
            'quantity',
            'unit_price',
            // 'vat',
            // 'note',
            // 'work_width',
            // 'work_height',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'price_htva',
            // 'price_tvac',
            // 'item_id',
            // 'extra_htva',
            // 'extra_amount',
            // 'extra_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
