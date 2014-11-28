<?php

//use kartik\widgets\FileInput;
//use yii\widgets\ActiveForm;
use app\models\Item;
use app\models\OrderLineDetail;
use app\models\Parameter;
use kartik\builder\Form;
use sjaakp\bandoneon\Bandoneon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$fineart_item = Item::findOne(['reference'=>'FineArts']);
?>
<div class="order-line-options">

	<h4 class="order-option" data-item_id="<?= $fineart_item->id ?>" data-item_name="<?= $fineart_item->libelle_long ?>">
		Fine Arts
	</h4>

    <div>

	<?=	Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [				
		        'tirage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Tirage', true),
					'options' => ['class' => 'form-control compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ']
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory('Finition'),
					'options' => ['class' => 'compute-price'],
		            'columnOptions' => ['colspan' => 5],
				],
		        'note' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 5],
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
					'items' => Item::getListForCategory('Chassis', true),
					'options' => ['class' => 'form-control  compute-price'],
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
					'options' => ['class' => 'form-control  compute-price'],
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
		        'collage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Collage', true),
					'options' => ['class' => 'form-control  compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_collage' => [
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
$("#orderlinedetail-tirage_id:enabled").trigger('change');
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);
