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

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Add Payment'), ['balance', 'id' => $client->id], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ? 
							in_array(Yii::$app->user->identity->role, ['manager', 'admin']) ? 
							Html::a($model->document->name, Url::to(['/order/document/view', 'id' => $model->document_id]))
							 : $model->document->name
						   : '';
	            },
	            'format' => 'raw',
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true
			],
	        [
				'attribute' => 'status',
	            'label' => Yii::t('store', 'Type'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
            [
				'attribute' => 'payment_method',
	            'value' => function ($model, $key, $index, $widget) {
	                return Parameter::getTextValue('paiement', $model->payment_method, '');
	            },
			],
            'note',
//            [
//				'class' => 'kartik\grid\ActionColumn',
//			 	'template' => '{update} {delete}'
//			],
        ],
    ]); ?>

</div>
