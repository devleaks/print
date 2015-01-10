<?php

use app\models\Account;
use app\models\Parameter;
use yii\helpers\Html;
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

if(isSet($opening_balance)) {
	$color = $opening_balance < 0 ? 'danger' : 'success';
	$openingBalance = [[
		'columns' => [
			['content'=> 'Opening Balance', 'options'=>['colspan'=>3, 'class'=>'text-right '.$color]],
			['content'=> Yii::$app->formatter->asCurrency($opening_balance), 'options'=>['colspan'=>1, 'class'=>'text-right '.$color]],
			['content'=> Yii::$app->formatter->asDate($to_date), 'options'=>['colspan'=>4, 'class'=> $color]],
		],
		'options' => ['class'=>'skip-export'] // remove this row from export
	]];
} else $openingBalance = false;

if(isSet($closing_balance)) {
	$color = $closing_balance < 0 ? 'danger' : 'success';
	$closingBalance = [[
		'columns' => [
			['content'=> 'Closing Balance', 'options'=>['colspan'=>3, 'class'=>'text-right '.$color]],
			['content'=> Yii::$app->formatter->asCurrency($closing_balance), 'options'=>['colspan'=>1, 'class'=>'text-right '.$color]],
			['content'=> Yii::$app->formatter->asDate(date('Y-m-d', strtotime('now'))), 'options'=>['colspan'=>4, 'class'=> $color]],
		],
		'options' => ['class'=>'skip-export'] // remove this row from export
	]];
} else $closingBalance = false;

?>
<div class="account-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	    'afterHeader' => $openingBalance,
		'showFooter' => true,
	    'afterFooter' => $closingBalance,
		'panel' => [
	        'heading'=>'<h3>'.$this->title.'</h3>',
	        'before'=> false,
	        'footer'=> false,
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ? $model->document->name : '';
	            },
	            'format' => 'raw',
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Balance'),
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
	            'value' => function ($model, $key, $index, $widget) {
                    return Account::getBalance($model->client_id, $model->created_at);
	            },
				'noWrap' => true,
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
					return new DateTime($model->created_at);
				}
			],
            [
				'attribute' => 'payment_method',
	            'value' => function ($model, $key, $index, $widget) {
	                return Parameter::getTextValue('paiement', $model->payment_method, '');
	            },
			],
            'note',
        ],
    ]); ?>

</div>
