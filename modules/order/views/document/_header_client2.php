<?php

use kartik\helpers\Html as KHtml;
use yii\helpers\Html
use yii\helpers\Url

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>
<?= KHtml::panel([
	'heading' => $model->prenom.' '.$model->nom.($model->autre_nom ? ' - '.$model->autre_nom : '')
					.($upd_link ?
						Html::a('<i class="glyphicon glyphicon-pencil pull-right"></i>', Url::to(['/store/client/maj', 'id' => $model->id, 'type' => $type]))
					:	''),
	'headingTitle' => true,
	'body' => '<address>'
			. $model->adresse;
			. '<br>'.$model->code_postal.' '.$model->localite
			. (($model->pays != '' && !in_array(strtolower($model->pays), ['belgique','belgie','belgium'])) ? '<br>'.$model->pays : '')
			. '<br><br><abbr title="Phone"><i class="glyphicon glyphicon-home"></i></abbr> '.($model->bureau ? $model->bureau : Yii::t('store', 'No phone.'));
			. '<br><abbr title="Phone"><i class="glyphicon glyphicon-phone"></i></abbr> '.($model->gsm ? $model->gsm : Yii::t('store', 'No mobile phone.').' '.Yii::t('store', 'No SMS.'));
			. '<br><abbr title="Email"><i class="glyphicon glyphicon-envelope"></i></abbr> '.($model->email ? Html::mailto($model->email) : Yii::t('store', 'No email.'));
			. '<br><br><abbr title="VAT"><i class="glyphicon glyphicon-briefcase"></i></abbr> '.($model->numero_tva ? $model->numero_tva : Yii::t('store', 'No VAT.'));
			. '</address>',
]
) ?>
</div>