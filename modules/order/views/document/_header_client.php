<?php

use app\models\Parameter;
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>
<div>
    <?= DetailView::widget([
        'model' => $client,
		'buttons1' => '{update}',
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