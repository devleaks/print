<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'History');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'kartik\grid\SerialColumn'],

            'object_type:ntext',
            'object_id',
            'action',
            'summary',
            'note',
            [
				'attribute' => 'performer_id',
				'value' => function($model, $key, $index, $widget) {
					$performer = User::findOne($model->performer_id);
					return $performer ? $performer->username : '';
				}
			],
            'created_at',

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
