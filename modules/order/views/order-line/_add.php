<?php

//use yii\widgets\FileInput;
use app\assets\ItemAsset;
use app\models\OrderLine;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\helpers\Enum;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */
/* @var $form yii\widgets\ActiveForm */

ItemAsset::register($this);

// url to submit search terms and get result back
$url = \yii\helpers\Url::to(['order-line/item-list']);

// Script to initialize the selection based on the value of the select2 element
$initScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$url}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results); $("#orderline-unit_price").trigger("change");});
    }
}
SCRIPT;

?>

<div class="order-line-form">

    <h4><?= Yii::t('store', 'Add an Item') ?></h4>

    <?php
		if(! $form)
			$form = ActiveForm::begin([
				'type'    => ActiveForm::TYPE_VERTICAL,
		        'options' => ['enctype' => 'multipart/form-data'],
			]);
	?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 12,
			'attributes' => [       // 2 column layout
				'item_id' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass' => Select2::className(),
		            'columnOptions' => ['colspan' => 6],
					'options' => [
						'options' => ['placeholder' => Yii::t('store', 'Search for an item ...')],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'minimumInputLength' => 2,
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
							        $.ajax("'.$url.'?id=" + id, {
							            dataType: "json"
							        }).done(function(data) {
												$("#orderline-unit_price").val(data.results.item.prix_de_vente);
												$("#orderline-vat").val(data.results.item.taux_de_tva);
												$("#orderline-unit_price").trigger("change");
									});
						    	}
						     }',
						],
					],
				],
		        'work_width' => [
					'type' => Form::INPUT_TEXT
				],
		        'work_height' => [
					'type' => Form::INPUT_TEXT
				],
		        'due_date' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> DatePicker::classname(),
					'options' => ['pluginOptions' => [
		                'format' => 'yyyy-mm-dd',
		                'todayHighlight' => true
		            ]],
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>
	
	<?= Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 12,
		    'attributes' => [       // 1 column layout
		        'quantity' => [
					'label' => Html::label(Yii::t('store', 'Qté')),
					'type' => Form::INPUT_TEXT
				],
		        'unit_price' => [
					'label' => Html::label(Yii::t('store', 'Pc.')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'vat' => [
					'label' => Html::label(Yii::t('store', 'VAT')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'price_htva' => [
					'label' => Html::label(Yii::t('store', 'HTVA')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'price_tvac' => [
					'label' => Html::label(Yii::t('store', 'TVAC')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'extra_type' => [
					'label' => Html::label(Yii::t('store', 'Rebate/Supplement')),
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => array_merge(["" => ""], Parameter::getSelectList('extra', 'value_text')),
		            'columnOptions' => ['colspan' => 2],
				],
		        'extra_amount' => [
					'label' => Html::label('% / €'),
					'type' => Form::INPUT_TEXT
				],
		        'extra_htva' => [
					'label' => Html::label(Yii::t('store', '± €')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'final_htva' => [
					'label' => Html::label(Yii::t('store', 'HTVA')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'final_tvac' => [
					'label' => Html::label(Yii::t('store', 'TVAC')),
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'note' => [
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 11],
				],
			],
		])
	?>
	
	<hr>
	<?= $this->render('../order-line-detail/_options', [
		    'model' => $model,
		    'form' => $form,
		])
	?>

	<hr>
    <h4><?= Yii::t('store', 'Add Pictures') ?></h4>

	<?php
	    $items = array();
	    foreach($model->getPictures()->all() as $picture)
	        $items[] = Html::img($picture->getThumbnailUrl(), ['class'=>'file-preview-image', 'alt'=>$picture->name, 'title'=>$picture->name]);
	?>

    <?php
		$browser = Enum::getBrowser();
		$version = floatval($browser['version']);
		//echo Enum::array2table($browser, true);

		if($browser['code'] == 'safari' && $version <= 535) {
			echo $form->field($model, 'image[]')->fileInput();
		} else {
			echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
		        'options' => ['accept' => 'image/jpeg, image/png, image/gif', 'multiple' => true],
		        'pluginOptions' => [
		            'initialPreview'    => $items,
		            'initialCaption'    => Yii::t('store', 'Select pictures'),
		            'overwriteInitial'  => false
		        ]
		    ]);
		}
    ?>
	<hr>

</div>
