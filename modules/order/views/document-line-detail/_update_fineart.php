<?php

//use kartik\widgets\FileInput;
//use yii\widgets\ActiveForm;
use app\models\Item;
use app\models\DocumentLineDetail;
use app\models\Parameter;
use kartik\builder\Form;
use sjaakp\bandoneon\Bandoneon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="document-line-update-options-tirage">

	<h4>Tirage <larger>+</larger></h4>

	<div id="store-missing-data" class="alert alert-danger" role="alert"></div>	

    <div>

	<?=	Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [				
		        'tirage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Tirage', true),
					'options' => ['class' => 'form-control'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory('Finition'),
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
					'items' => Item::getListForCategory('Chassis', true),
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
					'items' => Item::getListForCategory('Support', true),
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
					'items' => Item::getListForCategory('Vernis de protection', true),
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
					'items' => Item::getListForCategory('Cadre', true),
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
console.log('setting fineart_price...');
$("#documentline-item_id").trigger('change');
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);
