<?php

use app\models\User;

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentArchiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Document Archives');
$this->params['breadcrumbs'][] = User::getRole() == 'compta' ? ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']] : ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-archive-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Document Archive'), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'document_type',
            'name',
            'due_date:date',
            'price_htva:currency',
            'price_tvac:currency',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
