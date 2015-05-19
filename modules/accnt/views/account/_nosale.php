<?php

use app\models\Payment;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */

$urlClient = Url::to(['/order/document/client-list']);

$initScriptClient = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$urlClient}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results);});
    }
}
SCRIPT;

?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'client_id')->widget(Select2::classname(), [
		'pluginOptions' => [
	        'minimumInputLength' => 3,
	        'ajax' => [
	            'url' => $urlClient,
	            'dataType' => 'json',
	            'data' => new JsExpression('function(params) { return {search:params.term}; }'),
	            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
	        ],
	        'initSelection' => new JsExpression($initScriptClient)
		],
		'pluginEvents' => [
   			"change" => 'function(e) {
				id=e.target.value;
				if (id !== "") {
			        $.ajax("'.$urlClient.'?id=" + id, {
			            dataType: "json"
			        }).done(function(data) {
						console.log(data);
						$("#payment-client_id").val(data.results.id);
					});
		    	}
		     }',
		]
	])
	?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'date')->widget(DatePicker::className(), [
			'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            	],
				'options' => ['data-intro' => "Vous devez mentionner une date de livraison pour la commande. Si la date de livraison n'a pas d'importance, entrez la date du jour."],
	]) ?>

	<?= $form->field($model, 'method')->dropDownList(Payment::getPaymentMethods()) ?>

    <?= $form->field($model, 'note')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
