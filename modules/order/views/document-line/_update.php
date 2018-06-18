<?php

use kartik\widgets\FileInput;
//use yii\widgets\ActiveForm;
use app\assets\ItemAsset;
use app\models\DocumentLine;
use app\models\Parameter;
use app\models\Item;
use kartik\builder\Form;
use kartik\helpers\Enum;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\JsExpression;
//use yii\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
/* @var $form yii\widgets\ActiveForm */

ItemAsset::register($this);

// url to submit search terms and get result back
$url = \yii\helpers\Url::to(['document-line/item-list']);
if(!isset($model->image_add)) $model->image_add = DocumentLine::IMAGE_ADD;

$model->quantity_virgule = number_format($model->quantity, 2, ',', '');
$model->unit_price_virgule = number_format($model->unit_price, 2, ',', '');
$model->vat_virgule = number_format($model->vat, 2, ',', '');
$model->extra_amount_virgule = number_format($model->extra_amount, 2, ',', '');
$model->work_width_virgule = number_format($model->work_width, 2, ',', '');
$model->work_height_virgule = number_format($model->work_height, 2, ',', '');

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

?>

<div class="document-line-form">

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 6,
			'attributes' => [       // 2 column layout
				'item_id' => [
					'type' => Form::INPUT_RAW,
					'value' => Html::label(Yii::t('store', 'Item')  , 'item', ['class' => 'control-label'])
					// <input type="text" id="documentline-item_id" class="form-control kv-hide input-md" name="DocumentLine[item_id]">
							 . Html::hiddenInput('item_id', $model->item_id, ['id' => 'documentline-item_id', 'name' => 'DocumentLine[item_id]'])
							 . Html::textInput('item', $model->item->libelle_long, ['id' => 'itemDescription', 'class' => 'form-control', 'readonly' => true]),
		            'columnOptions' => ['colspan' => 3],
				],
		        'work_width_virgule' => [
					'type' => Form::INPUT_TEXT
				],
		        'work_height_virgule' => [
					'type' => Form::INPUT_TEXT
				],
			]
		])
	?>
	
	<?= Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 6,
		    'attributes' => [       // 1 column layout
		        'quantity_virgule' => [
					'type' => Form::INPUT_TEXT
				],
		        'unit_price_virgule' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'vat_virgule' => [
					'type' => Form::INPUT_TEXT,
				//	'options' => ['readonly' => true]
				//	'type' => Form::INPUT_DROPDOWN_LIST,
				//	'items' => array_merge(["0" => "0 %", "6" => '6 %', '21' => '21 %']),
				],
		        'price_htva' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'price_htva' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'price_tvac' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
			],
		])
	?>

	<?= Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [       // 1 column layout
		        'extra_type' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => array_merge(["" => ""], Parameter::getSelectList('extra', 'value_text')),
				],
		        'extra_amount_virgule' => [
					'label' => Html::label('% / €'),
					'type' => Form::INPUT_TEXT,
				],
		        'extra_htva' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'final_htva' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
		        'final_tvac' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true]
				],
			],
		])
	?>
	<?= Html::activeHiddenInput($model, 'quantity') ?>
	<?= Html::activeHiddenInput($model, 'unit_price') ?>
	<?= Html::activeHiddenInput($model, 'vat') ?>
	<?= Html::activeHiddenInput($model, 'extra_amount') ?>
	<?= Html::activeHiddenInput($model, 'work_width') ?>
	<?= Html::activeHiddenInput($model, 'work_height') ?>

	<?= Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 6,
		    'attributes' => [
		        'note' => [
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 4],
				],
		        'due_date' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> DatePicker::classname(),
				'options' => ['pluginOptions' => [
	                'format' => 'yyyy-mm-dd',
	                'todayHighlight' => true
	            ]],
				],
			],
		])
	?>
	
	<?php
	 	$chroma_item  = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE]);
		$free_item = Item::findOne(['reference'=>Item::TYPE_MISC]);
		$tirages = Item::find()->where(['yii_category'=>['Tirage','Canvas']])->select('id')->column();
		$detail = $model->getDetail();
	 	if( $chroma_item && $model->item_id == $chroma_item->id)
			echo $this->render('../document-line-detail/_update_chroma', [
			    'model' => $model,
			    'form' => $form,
				'detail' => $detail
		    ]);
		else if(in_array($model->item_id, $tirages))
			echo $this->render('../document-line-detail/_update_fineart', [
			    'model' => $model,
			    'form' => $form,
				'detail' => $detail
		    ]);
		else if($model->item_id == $free_item->id)
			echo $this->render('../document-line-detail/_update_free');
	?>


	<?= $this->render('_pictures_edit', [
		    'model' => $model,
		    'form' => $form,
		])
	?>
	
    <?php
/*		$browser = Enum::getBrowser();
		$version = floatval($browser['version']);
		//echo Enum::array2table($browser, true);

		if($browser['code'] == 'safari' && $version <= 535) {
			echo $form->field($model, 'image[]')->fileInput();
		} else {
*/			$items = [];
		    foreach($model->getPictures()->all() as $picture)
				$items[] = Html::img($picture->getThumbnailUrl());

			echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
		        'options' => ['accept' => 'image/jpeg, image/png, image/gif', 'multiple' => true],
		        'pluginOptions' => [
		            'initialPreview'    => $items,
		            'initialCaption'    => Yii::t('store', 'Select pictures'),
		            'overwriteInitial'  => false
		        ]
		    ]);
/*		}
*/    ?>

	<?= Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 1,
		    'attributes' => [
		        'image_add' => [
					'type' => Form::INPUT_RADIO_LIST,
					'label' => false,
					'options' => ['inline'=>true],
					'items' => [
						DocumentLine::IMAGE_ADD => Yii::t('store', 'Add'),
						DocumentLine::IMAGE_REPLACE => Yii::t('store', 'Replace')
					]
				],
			],
		])
	?>
	

	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Update Order Line'), ['class' => 'btn btn-primary']) ?>
    </div>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_INIT'); ?>
$("#documentline-quantity").trigger('change');
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);
