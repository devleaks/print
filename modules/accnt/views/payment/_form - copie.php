<?php

use app\models\Payment;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;
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

$urlSale = Url::to(['/order/document/document-list']);

$initScriptSale = <<< SCRIPT
function (element, callback) {
    var sale=\$(element).val();
    if (sale !== "") {
        \$.ajax("{$urlSale}?sale=" + sale, {
            dataType: "json"
        }).done(function(data) { callback(data.results); });
    }
}
SCRIPT;

?>

<div class="payment-form">
	
	<div class="alert alert-danger">
		Vous modifiez directement des paiements.
		Toute mauvaise manipulation peut entraîner des erreurs dans la comptabilité.
	</div>

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
						$("#payment-client_id").val(data.results.id);
					});
		    	}
		     }',
		]
	])->label(Yii::t('store', 'Client'))
	?>

	<?= $form->field($model, 'sale')->widget(Select2::classname(), [
		'pluginOptions' => [
	        'minimumInputLength' => 3,
	        'ajax' => [
	            'url' => $urlSale,
	            'dataType' => 'json',
	            'data' => new JsExpression('function(params) { return {search:params.term}; }'),
	            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
	        ],
	        'initSelection' => new JsExpression($initScriptSale)
		],
		'pluginEvents' => [
   			"change" => 'function(e) {
				if (sale !== "") {
			        $.ajax("'.$urlSale.'?sale=" + sale, {
			            dataType: "json"
			        }).done(function(data) {
						$("#payment-sale").val(data.results.id);
					});
		    	}
		     }',
		]
	])->label(Yii::t('store', 'Sale'))
	?>
	

	<?= $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [
		'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true
        	],
		]);
	?>
    

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'note')->textInput() ?>

	<?php if($model->payment_method == Payment::CASH): ?>
		<?= Yii::t('store', 'Payment Method').': '.$model->payment_method.' - '.Yii::t('store', 'Cannot be changed here') ?>
	<?php else: ?>
		<?= $form->field($model, 'payment_method')->dropDownList(Payment::getPaymentMethods(true)) ?>
	<?php endif; ?>

	<?= $form->field($model, 'status')->dropDownList([Payment::STATUS_PAID => Yii::t('store', Payment::STATUS_PAID), Payment::STATUS_OPEN => Yii::t('store', Payment::STATUS_OPEN)]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
