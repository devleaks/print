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
		 <?= Html::a(Yii::t('store', 'Reconsile Transactions'), ['reconsile'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
			[
				'attribute' => 'execution_date',
				'format' => 'date',
				'hAlign' => GridView::ALIGN_CENTER,
				'noWrap' => true,
			],
			[
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
            [
				'attribute' => 'source',
				'noWrap' => true,
			],
            [
				'attribute' => 'note',
				'noWrap' => true,
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget) {
					return substr($model->note, 0, 10).'&nbsp;...';
				},
				'options' => ['width' => '40%'],
			],
            // 'account',
            // 'status',
            // 'created_at',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
