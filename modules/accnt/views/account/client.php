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
?>
<div class="account-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	    'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//			'ref',
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true
			],
			[
	            'label' => Yii::t('store', 'Account'),
				'attribute' => 'account',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
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
//            [
//				'class' => 'kartik\grid\ActionColumn',
//			 	'template' => '{update} {delete}'
//			],
        ],
    ]); ?>

</div>
