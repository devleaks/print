<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cash');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
		<?= $this->render('_search', ['model' => $searchModel]) ?>

	    <div class="col-lg-6">
		<?= Html::a(Yii::t('store', 'Create Cash Transaction'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
	    </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'payment_date',
				'format' => 'datetime',
//				'filterType' => GridView::FILTER_DATE,
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->payment_date);
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

            //'id',
            //'document_id',
            //'sale',
			[
				'label' => Yii::t('store', 'Reference'),
				'attribute' => 'document.name',
				'noWrap' => true,
			],
            'note',
			[
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
            //'payment_date',
			[
				'label' => Yii::t('store', 'Cash'),
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getBalance($model->created_at);
	            },
			],
            // 'updated_at',
            // 'updated_by',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
