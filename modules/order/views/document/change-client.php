<?php

use app\models\Document;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
$url = Url::to(['client-list']);
$docty = $model->document_type;
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

$this->title = Yii::t('store', 'Change Client');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', ucfirst(strtolower($model->document_type).'s')), 'url' => [strtolower($model->document_type).'s', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/order/document/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
		/** If create new doc, form is opened and closed here; there is a single form for the entire page */
		$form = ActiveForm::begin(); ?>

    <div class="row">
	
		<div class="col-lg-6">
			<div>
			<?= $this->render('_header_client', ['client' => $model->getClient()->one()]) ?>
			</div>
		</div>
	
		<div class="col-lg-6">
		<div class="row">
        <div class="col-lg-12">
		<?= Form::widget([
				    'model' => $model,
				    'form' => $form,
				    'columns' => 6,
				    'attributes' => [				
				        'client_id' => [
							'label' => Yii::t('store', 'New Client'),
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
					],
				])
		?>
		</div>
		<br/><br/>
		<div class="col-lg-12" data-intro="Client. Si les données ne sont pas affichées, veuillez choisir un client à gauche." data-position='bottom'>
			<div id="clientDetails"></div>

		    <div class="form-group">
		        <?= Html::submitButton(Yii::t('store', 'Change Client'), ['class' => 'btn btn-primary']) ?>
		    </div>
		</div>
		
		</div>

		</div>
		
	</div>


    <?php ActiveForm::end(); ?>

</div>
