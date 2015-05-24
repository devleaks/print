<?php

use app\models\DocumentLineDetail;
use app\models\Item;
use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$detail->tirage_factor_virgule = str_replace('.',',',$detail->tirage_factor);

?>
<div class="document-line-update-options-tirage">

	<h4>Tirage <larger>+</larger></h4>

	<div id="store-missing-data" class="alert alert-danger" role="alert"></div>	

    <div>

	<?= Html::activeHiddenInput($detail, 'tirage_factor') ?>	
	<?=	Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [				
		        'tirage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::TIRAGE, true) + Item::getListForCategory(ItemCategory::CANVAS),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 3],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
		        'tirage_factor_virgule' => [
					'type' => Form::INPUT_TEXT,
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory(ItemCategory::TIRAGE_PARAM),
		            'columnOptions' => ['colspan' => 5],
					'options' => ['inline'=>true],
				],
			],
		]).
		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [	
		        'chassis_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::CHASSIS, true),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_chassis' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
			],
		]).
		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [		
		        'support_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::SUPPORT, true),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_support' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
			],
		]).
		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'protection_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::PROTECTION, true),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_protection' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
			],
		]).
		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'corner_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 5],
				],			
		        'frame_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::FRAME, true),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_frame' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control'],
				],

		        'montage_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_montage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control'],
				],

		        'renfort_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_renfort' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control'],
				],
		        'filmuv_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 4],
				],			
		        'price_filmuv' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
			],
		])
	?>

	</div>

</div>
<?= $this->render('_js_load_data') ?>

<script type="text/javascript">
<?php
$this->beginBlock('JS_INIT'); ?>
-item_id").trigger('change');
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);
