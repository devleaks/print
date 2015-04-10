<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BankTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Bank Transactions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-transaction-index">

    <h1><?= Html::encode($this->title) ?>
		 <?= Html::a(Yii::t('store', 'Upload Bank Transaction'), ['upload'], ['class' => 'btn btn-primary']) ?>
		 <?= Html::a(Yii::t('store', 'Reconsile Transactions'), ['reconsile'], ['class' => 'btn btn-warning']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],

            //'id',
            'name',
            'execution_date',
            'value_date',
            'amount',
            // 'currency',
            'source',
            'note',
            // 'account',
            // 'status',
            // 'created_at',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
