<?php

use app\models\Parameter;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;

$this->title = Yii::t('store', 'Customer {0}', [$client->niceName()]);
$this->params['breadcrumbs'][] = ['label' => in_array($role, ['manager', 'admin']) ? Yii::t('store', 'Management') : Yii::t('store', 'Accounting'),
								  'url'   => [in_array($role, ['manager', 'admin']) ? '/store' : '/accnt']];
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;
?>
<div class="account-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	    'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//			'ref',
			[
	            'label' => Yii::t('store', 'Debit'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
				'value' => function ($model, $key, $index, $widget) {
					return $model->amount < 0 ? $model->amount : '';
				}
			],
			[
	            'label' => Yii::t('store', 'Credit'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
				'value' => function ($model, $key, $index, $widget) {
					return $model->amount > 0 ? $model->amount : '';
				}
			],
			[
				'attribute' => 'note',
				'format' => 'raw',
			],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date);
				}
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'account',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => Yii::$app->formatter->asCurrency($bottomLine),
			],
//            [
//				'class' => 'kartik\grid\ActionColumn',
//			 	'template' => '{update} {delete}'
//			],
        ],
    ]); ?>

</div>
