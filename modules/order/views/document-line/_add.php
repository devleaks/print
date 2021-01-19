<?php

use app\models\Item;
use app\assets\ItemAsset;
use app\models\DocumentLine;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\helpers\Enum;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
/* @var $form yii\widgets\ActiveForm */

ItemAsset::register($this);

$chroma_item  = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE]);
$misc_item  = Item::findOne(['reference'=>Item::TYPE_MISC]);

// url to submit search terms and get result back
$url = Url::to(['document-line/item-list']);

if(!isset($model->quantity) || $model->quantity == '') {
	$model->quantity = 1;
	$model->quantity_virgule = 1;
}

// Script to initialize the selection based on the value of the select2 element
$initScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$url}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results); $("#documentline-unit_price").trigger("change");});
    }
}
SCRIPT;
$do_form = $form;
?>

<div class="document-line-form">

    <?php
		if(! $do_form)
			$form = ActiveForm::begin([
				'type'    => ActiveForm::TYPE_VERTICAL,
		        'options' => ['enctype' => 'multipart/form-data'],
				'id' => 'documentline-form',
			]);
	?>
	<div data-intro="Nouvelle ligne de commande" data-position='top'>
	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 12,
			'attributes' => [       // 2 column layout
				'item_id' => [
					'label' => Yii::t('store', 'Item')/*.' <span class="label label-warning order-option" data-item_id="'.$chroma_item->id.'" data-item_name="'.$chroma_item->libelle_long.'" data-item-category="ChromaLuxe">ChromaLuxe</span>'
													  .' <span class="label label-success order-option" data-item_id="'.$misc_item->id.'" data-item_name="'.$misc_item->libelle_long.'" data-item-category="Divers">Divers</span>'*/,
					'type' => Form::INPUT_WIDGET,
					'widgetClass' => Select2::className(),
		            'columnOptions' => ['colspan' => 6],
					'options' => [
						'options' => ['placeholder' => Yii::t('store', 'Search for an item ...')/*, 'id' => 'main_item_select'*/],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'minimumInputLength' => 2,
					        'ajax' => [
					            'url' => $url,
					            'dataType' => 'json',
					            'data' => new JsExpression('function(params) { return {search:params.term}; }'),
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
										$("#documentline-unit_price").val(data.results.item.prix_de_vente);
										$("#documentline-vat").val(data.results.item.taux_de_tva);
										$("#documentline-item-yii_category").val(data.results.item.yii_category);
										$("#documentline-unit_price").trigger("change");
									});
						    	}
						     }',
						],
					],
				],
		        'work_width_virgule' => [
					'type' => Form::INPUT_TEXT
				],
		        'work_height_virgule' => [
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
		        'quantity_virgule' => [
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
		        'extra_amount_virgule' => [
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
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 11],
				],
			],
		])
	?>
	<!--added to hold _virgule inputs number values-->
	<?= Html::activeHiddenInput($model, 'quantity') ?>
	<?= Html::activeHiddenInput($model, 'extra_amount') ?>
	<?= Html::activeHiddenInput($model, 'work_width') ?>
	<?= Html::activeHiddenInput($model, 'work_height') ?>
	<?= Html::hiddenInput('yii_category', ($model->item ? $model->item->yii_category : ''), ['id'=>'documentline-item-yii_category']) ?>
	</div>

	<?= $this->render('../document-line-detail/_options', [
		    'model' => $model,
		    'form' => $form,
		])
	?>
	
	<div data-intro="Joindre des images à la ligne de commande" data-position='top'>
	<?php
	    $items = array();
	    foreach($model->getPictures()->all() as $picture)
	        $items[] = Html::img($picture->getThumbnailUrl(), ['class'=>'file-preview-image', 'alt'=>$picture->name, 'title'=>$picture->name]);
	?>

    <?php
/*
		$browser = Enum::getBrowser();
		$version = floatval($browser['version']);
		//echo Enum::array2table($browser, true);

		if($browser['code'] == 'safari' && $version <= 535) {
			echo $form->field($model, 'image[]')->fileInput();
		} else {
*/			echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
		        'options' => ['accept' => 'image/jpeg, image/png, image/gif', 'multiple' => true],
		        'pluginOptions' => [
		            'initialPreview'    => $items,
		            'initialCaption'    => Yii::t('store', 'Select pictures'),
		            'overwriteInitial'  => false
		        ]
		    ]);
/*		}
*/    ?>
	</div>

	<?php if(! $do_form): ?>
	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('store', 'Add Item'), ['class' => 'btn btn-primary', 'id' => 'documentlinedetail-submit']) ?>
	    </div>
	    <?php ActiveForm::end(); ?>
	<?php endif; ?>

</div>
