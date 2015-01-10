<?php

use app\models\Document;
use app\models\Parameter;
use app\models\User;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

// url to submit search terms and get result back
$url = \yii\helpers\Url::to(['client-list']);
$docty = $model->document_type;
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
				    'columns' => 6,
				    'attributes' => [				
				        'client_id' => [
							'type' => Form::INPUT_WIDGET,
				            'columnOptions' => ['colspan' => 5],
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
						'actions' => [    // embed raw HTML content
				            'type' => Form::INPUT_RAW, 
				            'value'=> Html::label('&nbsp;').Html::a(Yii::t('store', 'Create Client'), Url::to(['/store/client/new', 'ret' => $model->document_type]),
								['class' => 'btn btn-warning', 'data-intro' => "Pour aller vers l'écran d'ajout d'un nouveau client"]),
				        ]						
					],
				])
		?>
		</div>
		<div  data-intro="Informations générales sur la commande" data-position='right'>
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
									 . Html::textInput('days', 0, ['id' => 'daysComputed', 'class' => 'form-control', 'readonly' => true]),
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
				        'legal' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => array_merge([""=>""] , Parameter::getSelectList('legal', 'value_text')),
				            'columnOptions' => ['colspan' => 6],
						],
				        'note' => [
							'type' => Form::INPUT_TEXT,
				            'columnOptions' => ['colspan' => 6],
						],
					],
				])
		?>
		</div>
		</div>
		
		<div class="col-lg-1">
		</div>
		
		<div class="col-lg-5" data-intro="Client. Si les données ne sont pas affichées, veuillez choisir un client à gauche." data-position='bottom'>
			<div id="clientDetails"></div>
		</div>

	</div>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_DATEDIFF'); ?>
function parseDate(str) { // str like '2014-10-27'
    var ymd = str.split('-');
    return new Date(ymd[0], ymd[1]-1, ymd[2]);
}
function daydiff(first, second) {
    return Math.floor( (second-first)/(1000*60*60*24) ) + 1;
}
$("#document-due_date").change(function() {
	then = parseDate($(this).val());
	days = daydiff(new Date(), then); // values to be loaded from db...
	     if (days < 3) message = "danger";
	else if (days < 5) message = "warning";
	else if (days < 7) message = "info";
	else               message = "success";
	console.log('adding '+message);
	$('#daysComputed').val(days).parent()
		.removeClass('bg-danger')
		.removeClass('bg-warning')
		.removeClass('bg-info')
		.removeClass('bg-success')
		.addClass('bg-'+message);
	
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_DATEDIFF'], yii\web\View::POS_END);




