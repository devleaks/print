<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'client.nom',
            'amount',
            'payment_date',
            'payment_method',
            'note',
            'status',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'controller' => 'account',
				'template' => '{view}',
			],
        ],
    ]); ?>

</div>
