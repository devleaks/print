<?php

use app\models\User;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cash');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'id',
            //'document_id',
            //'sale',
			[
				'label' => Yii::t('store', 'Reference'),
				'attribute' => 'document.name',
				'noWrap' => true,
			],
			[
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
            'note',
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
//				'filterType' => GridView::FILTER_DATE,
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				},
				'noWrap' => true,
			],
	        [
				'attribute' => 'created_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getUpdatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],


            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {custom-delete}',
	            'buttons' => [
	                'custom-delete' => function ($url, $model) {
						$url = Url::to(['delete', 'id'=> $model->id]);
	                    return $model->sale ? '' :
								Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
									'title' => Yii::t('store', 'Delete'),
	                        		'data-method' => 'post',
	                        		'data-confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
								]
								);
	                },
				],

			],
        ],
    ]); ?>

</div>
