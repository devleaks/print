<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProviderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Order Frames');

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'reference',
			[
				'attribute' => 'due_date',
				'format' => 'date'
			],
            'provider',
            'provider_email:email',
			'item',
			'width',
			'height',
			'quantity',
			'note',
        ],
    ]); ?>

	<?= Html::a(Yii::t('store', 'Send Orders'), ['send-orders', 'ids' => $ids], ['class' => 'btn btn-primary']) ?>

</div>
