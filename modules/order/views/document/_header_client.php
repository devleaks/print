<?php

use app\models\Account;
use app\models\Bill;
use app\models\Parameter;
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
$unpaid = Account::getBalance($client->id);
$account_color = $unpaid < 0 ? 'warning' : 'success';
$unpaid = Bill::find()->andWhere(['!=','document.status',Bill::STATUS_CLOSED])
					  ->andWhere(['client_id' => $client->id])->exists();
$unpaid_color = $unpaid < 0 ? 'warning' : 'success';

$buttons1 = '<span class="kv-buttons-1">'.
Html::a('<span class="glyphicon glyphicon-book"></span>',
  ['/accnt/account/client', 'id' => $client->id], [
	'title' => Yii::t('store', 'Client Account'),
	'class' => "btn btn-xs btn-$account_color kv-btn-book",
	'target' => 'blank',
 ])
.' '.
Html::a('<span class="glyphicon glyphicon-euro"></span>',
  ['/accnt/bill/client-unpaid', 'id' => $client->id], [
	'title' => Yii::t('store', 'Unpaid Bills'),
	'class' => "btn btn-xs btn-$unpaid_color kv-btn-book",
	'target' => 'blank',
])
.' '.
Html::a('<span class="glyphicon glyphicon-shopping-cart"></span>',
  ['/order/document/client', 'id' => $client->id], [
	'title' => Yii::t('store', 'Previous Orders'),
	'class' => "btn btn-xs btn-primary kv-btn-book",
	'target' => 'blank',
])
.'</span>';

//$morebuttons = '<span class="kv-buttons-1"><button type="button" class="btn btn-xs btn-default kv-btn-update" title="Compte client"><span class="glyphicon glyphicon-book"></span></button></span>';
?>
<div>
    <?= DetailView::widget([
        'model' => $client,
		'buttons1' => '{update}'.' '.$buttons1,
		'panel'=>[
	        'heading' => '<h4>'.$client->prenom . ' '.$client->nom . ($client->autre_nom ? ' - '.$client->autre_nom : '') .'</h4>',
	    ],
		'formOptions' => [
			'action' => Url::to(['/store/client/live-update', 'id'=>$client->id]),
		],
        'attributes' => [
            //'id',
            //'client_id',
			'adresse',
			'code_postal',
			'localite',
			[
				'attribute' => 'pays',
				'visible' => $client->pays,
			],
			[
	        	'attribute' => 'lang',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(Parameter::find()->where(['domain'=>'langue'])->orderBy('value_int')->asArray()->all(), 'name', 'value_text'),
				'value' => Parameter::getTextValue('langue', ($client->lang ? $client->lang : 'fr')),
			],
			[
				'attribute' => 'email',
			],
			[
				'attribute' => 'gsm',
			],
			[
				'attribute' => 'domicile',
				'visible' => $client->domicile,
			],
			[
				'attribute' => 'bureau',
				'visible' => $client->bureau,
			],
			[
				'attribute' => 'numero_tva',
				'visible' => $client->numero_tva,
			],
        ],
    ]) ?>
</div>