<?php

use app\models\Item;
use app\models\OrderLineDetail;
use app\models\Parameter;
use kartik\builder\Form;
use sjaakp\bandoneon\Bandoneon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */
/* @var $form yii\widgets\ActiveForm */

$detail = new OrderLineDetail();
$detail->order_line_id = $model->id;

$chroma_item = Item::findOne(['reference'=>'1']);
$fineart_item = Item::findOne(['reference'=>'FineArts']);
$free_item = Item::findOne(['reference'=>'#']);
$class_prefix = 'item';
if($detail->free_item_vat == '') $detail->free_item_vat = 21;
/*
Class compute-price added to all items that provoque adjustment of option prices
Class set-unit-price added to all price options that provoque adjustment of item unit price
 */
?>
<div class="order-line-options">

    <h4><?= Yii::t('store', 'Special Item') ?></h4>

	<?php Bandoneon::begin() ?>

	<h4 class="order-option"
			data-item_id="<?= $chroma_item->id ?>"
			data-item_name="<?= $chroma_item->libelle_long ?>"
			data-item_vat="<?= $chroma_item->taux_de_tva ?>"
	>
		<span style="color: #eea236;">Chroma</span>Luxe
	</h4>

    <div>
	
	<div id="store-missing-data" class="alert alert-danger" role="alert">
	</div>
	
	<?= Form::widget([
				    'model' => $detail,
				    'form' => $form,
				    'columns' => 5,
				    'attributes' => [

				        'chroma_id' => [
							'type' => Form::INPUT_RADIO_LIST,
							'items' => Item::getListForCategory('ChromaType'),
				            'columnOptions' => ['colspan' => 4],
							'options' => ['inline'=>true, 'class' => $class_prefix . $chroma_item->id . ' compute-price'],
						],
				        'price_chroma' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
						],

				        'corner_bool' => [
							'type' => Form::INPUT_CHECKBOX,
				            'columnOptions' => ['colspan' => 5/*4*/],
							'options' => ['class' => $class_prefix . $chroma_item->id. ' compute-price'],
						],			
						/*			
				        'price_corner' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true, 'class' => $class_prefix . $chroma_item->id],
						],*/
				        'frame_id' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => Item::getListForCategory('Cadre', true),
							'options' => ['class' => 'form-control '.$class_prefix . $chroma_item->id. ' compute-price'],
				            'columnOptions' => ['colspan' => 4],
						],
				        'price_frame' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
						],

				        'montage_bool' => [
							'type' => Form::INPUT_CHECKBOX,
							'options' => ['class' => $class_prefix . $chroma_item->id. ' compute-price'],
				            'columnOptions' => ['colspan' => 4],
						],
				        'price_montage' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
						],

				        'renfort_bool' => [
							'type' => Form::INPUT_CHECKBOX,
							'options' => ['class' => $class_prefix . $chroma_item->id. ' compute-price'],
				            'columnOptions' => ['colspan' => 4],
						],
				        'price_renfort' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
						],
					],
				])
	?>

	</div>
	
	<h4 class="order-option"
			data-item_id="<?= $fineart_item->id ?>"
			data-item_name="<?= $fineart_item->libelle_long ?>"
			data-item_vat="<?= $fineart_item->taux_de_tva ?>"
	>
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
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id. ' compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory('Finition'),
					'options' => ['inline'=>true, 'class' => $class_prefix . $fineart_item->id. ' compute-price'],
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
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id. ' compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_support' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
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
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id. ' compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_protection' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
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
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id. ' compute-price'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_collage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
				],
			],
		])
	?>

	</div>

	<h4 class="order-option"
			data-item_id="<?= $free_item->id ?>"
			data-item_name="<?= $free_item->libelle_long ?>"
			data-item_vat="<?= $free_item->taux_de_tva ?>"
			>
		<?= Yii::t('store', 'Free Text Item') ?>
	</h4>

    <div>
	
	<?= Form::widget([
				    'model' => $detail,
				    'form' => $form,
				    'columns' => 6,
				    'attributes' => [
				        'free_item_libelle' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['class' => 'form-control '. $class_prefix . $free_item->id],
				            'columnOptions' => ['colspan' => 4],
						],
				        'free_item_price_htva' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=>MaskedInput::className(),
							'options' => ['clientOptions' => [
							        'alias' =>  'decimal',
 									'radixPoint' => ",",
							        'groupSeparator' => '',
							        'autoGroup' => false
								]
							],	
						],
				        'free_item_vat' => [
							'type' => Form::INPUT_WIDGET,
							'widgetClass'=>MaskedInput::className(),
							'options' => ['clientOptions' => [
							        'alias' =>  'decimal',
 									'radixPoint' => ",",
							        'groupSeparator' => '',
							        'autoGroup' => false
								], 
								'class' => 'form-control input-group '.$class_prefix . $free_item->id
							],	
							'fieldConfig' => ['addon' => ['append' => ['content'=>'%']]],
						],
					],
				])
	?>

	</div>

	<?php Bandoneon::end() ?>


</div>
<?= $this->render('_js_load_data') ?>