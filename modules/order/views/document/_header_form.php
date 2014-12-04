<?php

//use yii\widgets\ActiveForm;
use app\assets\DateDiff;
use app\models\Document;
use app\models\User;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */

// url to submit search terms and get result back
$url = \yii\helpers\Url::to(['client-list']);
$docty = $model->document_type;

DateDiff::register($this);

if(!isset($model->due_date) || $model->due_date == '') $model->due_date = date('Y-m-d');

// Script to initialize the selection based on the value of the select2 element
$initScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$url}?id=" + id + "&ret=$docty", {
            dataType: "json"
        }).done(function(data) { $("#clientDetails").html(data.results.addr); callback(data.results);});
    }
}
SCRIPT;

?>

<div class="order-header-form">

    <div class="row">
	
        <div class="col-lg-6">
		<div id="client-intro" data-intro="Client de la commande.
Il est obligatoire d'en préciser un.
Vous pouvez soit retrouvez un client dans la base de données, soit en ajouter un nouveau">
		<?= Form::widget([
				    'model' => $model,
				    'form' => $form,
				    'attributes' => [				
				        'client_id' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> Select2::classname(),
							'options' => [
								'pluginOptions' => [
							        'allowClear' => true,
							        'minimumInputLength' => 3,
							        'ajax' => [
							            'url' => $url,
							            'dataType' => 'json',
							            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
							            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
							        ],
							        'initSelection' => new JsExpression($initScript)
								],
								'pluginEvents' => [
					    			"change" => 'function(e) {
										id=e.target.value;
										if (id !== "") {
									        $.ajax("'.$url.'?id=" + id + "&ret='.$docty.'", {
									            dataType: "json"
									        }).done(function(data) {
												$("#clientDetails").html(data.results.addr);
											});
								    	}
								     }',
								],
							],
						],
					],
				])
		?>
	
		<?= Html::a(Yii::t('store', 'Create Client'), ['../store/client/new', 'ret' => $model->document_type],
		['class' => 'btn btn-warning', 'data-intro' => "Pour aller vers l'écran d'ajout d'un nouveau client"]) ?>

		<p></p>
		</div>
			
		<?= Form::widget([
				    'model' => $model,
				    'form' => $form,
				    'columns' => 6,
				    'attributes' => [				
				        'due_date' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> DatePicker::classname(),
							'options' => ['pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            	],
								'options' => ['data-intro' => "Vous devez mentionner une date de livraison pour la commande. Si la date de livraison n'a pas d'importance, entrez la date du jour."],
							],
				            'columnOptions' => ['colspan' => 2],
						],
				        'days' => [
							'type' => Form::INPUT_RAW,
				            'columnOptions' => ['colspan' => 1],
							'label' => Yii::t('store', 'Delay'),
							'value' => Html::label(Yii::t('store', 'Delay')  , 'days', ['class' => 'control-label'])
									 . Html::textInput('days', 8, ['id' => 'daysComputed', 'class' => 'form-control', 'readonly' => true]),
						],
						'created_by' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => User::getList(),
				            'columnOptions' => ['colspan' => 2],
						],
				        'vat_bool' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> SwitchInput::className(),
							'options' => [
							    'pluginOptions' => [
									'onText' => Yii::t('store', 'Yes'),
									'offText' =>  Yii::t('store', 'No')
								]
							],
				            'columnOptions' => ['colspan' => 1],
						],
				        'reference_client' => [
							'type' => Form::INPUT_TEXT,
				            'columnOptions' => ['colspan' => 5],
						],
				        'bom_bool' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=> SwitchInput::className(),
							'options' => [
							    'pluginOptions' => [
									'onText' => Yii::t('store', 'Yes'),
									'offText' =>  Yii::t('store', 'No')
								],
							],
				            'columnOptions' => ['colspan' => 1],
						],
				        'note' => [
							'type' => Form::INPUT_TEXTAREA,
				            'columnOptions' => ['colspan' => 6],
						],
					],
				])
		?>
		
		</div>
		
		<div class="col-lg-1">
		</div>
		
		<div class="col-lg-5">
			<div id="clientDetails"></div>
		</div>

	</div>

</div>