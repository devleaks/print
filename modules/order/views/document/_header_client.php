<?php

use app\models\Account;
use app\models\Bill;
use app\models\Client;
use app\models\Parameter;
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
$unpaid = false; //Bill::find()->andWhere(['!=','document.status',Bill::STATUS_CLOSED])->andWhere(['client_id' => $client->id])->exists();
$unpaid_color = $unpaid ? 'warning' : 'primary';

$bottomLine = 0; // $client->getBottomLine();

$client_color = $bottomLine < -0.04 ? 'warning' : 'primary';

$buttons1 = $client->isComptoir() ? '' : '{update} <span class="kv-buttons-1">'.
Html::a('<i class="glyphicon glyphicon-shopping-cart"></i>',
  ['/order/document/client', 'id' => $client->id, 'sort' => '-updated_at'], [
	'title' => Yii::t('store', 'Previous Orders'),
	'class' => "btn btn-xs btn-primary kv-btn-book",
	'target' => '_blank',
])
.' '.
Html::a('<i class="glyphicon glyphicon-euro"></i>',
  ['/accnt/bill/client-unpaid', 'id' => $client->id], [
	'title' => Yii::t('store', 'Unpaid Bills'),
	'class' => "btn btn-xs btn-$unpaid_color kv-btn-book",
	'target' => '_blank',
])
.' '.
Html::a('<i class="glyphicon glyphicon-book"></i>',
  ['/accnt/account/client', 'id' => $client->id], [
	'title' => Yii::t('store', 'Client Account'.' - '.$bottomLine),
	'class' => "btn btn-xs btn-$client_color kv-btn-book",
	'target' => '_blank',
])
.'</span>';

//$morebuttons = '<span class="kv-buttons-1"><button type="button" class="btn btn-xs btn-default kv-btn-update" title="Compte client"><i class="glyphicon glyphicon-book"></i></button></span>';
?>
<div>
    <?= DetailView::widget([
        'model' => $client,
		'buttons1' => $buttons1,
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
            [
                'attribute'=>'reference_interne',
            ],
            [
                'attribute'=>'comptabilite',
				'displayOnly' => true,
            ],
            [
                'attribute'=>'commentaires',
            ],
        ],
    ]) ?>
</div>