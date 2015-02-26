<?php

use app\models\Event;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */

$model->status = $model->status == $model::STATUS_ACTIVE;
?>

<div class="event-form">

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
	]); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 12,
			'attributes' => [
		        'name' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'event_type' => [
					'type' => Form::INPUT_DROPDOWN_LIST	,
					'items' => Parameter::getSelectList('event_type', 'value_text'),
		            'columnOptions' => ['colspan' => 2],
				],
		        'date_from' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> DatePicker::className(),
					'options' => ['pluginOptions' => [
		                'format' => 'yyyy-mm-dd',
		                'todayHighlight' => true
		            	],
						'options' => ['data-intro' => "Vous devez mentionner une date de livraison pour la commande. Si la date de livraison n'a pas d'importance, entrez la date du jour."],
					],
		            'columnOptions' => ['colspan' => 2],
				],
		        'date_to' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> DatePicker::className(),
					'options' => ['pluginOptions' => [
		                'format' => 'yyyy-mm-dd',
		                'todayHighlight' => true
		            	],
						'options' => ['data-intro' => "Vous devez mentionner une date de livraison pour la commande. Si la date de livraison n'a pas d'importance, entrez la date du jour."],
					],
		            'columnOptions' => ['colspan' => 2],
				],
		        'status' => [
					'label' => Html::label(Yii::t('store', 'Status')).'<br>',
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=>SwitchInput::className(),
					'options' => ['pluginOptions' => [
								'onText' => Yii::t('store', 'Active'),
								'offText' =>  Yii::t('store', 'Inactive'),
						        'onColor' => 'success',
						        'offColor' => 'danger',
								'state' => $model->status == Event::STATUS_ACTIVE
					]],
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
