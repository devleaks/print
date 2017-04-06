<?php

use app\models\DocumentLineDetail;
use app\models\Item;
use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\helpers\VarDumper;
/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
/* @var $form yii\widgets\ActiveForm */

$detail = new DocumentLineDetail();
$detail->document_line_id = $model->id;

$class_prefix = 'item';

if($detail->free_item_vat == '') $detail->free_item_vat = 21;
?>
<div class="document-line-options" data-intro="Lignes de commande rapides: ChromaLuxe, tirage, et article libre.
Cliquer sur l'onglet pour sélectionner cet article et révéler un panneau pour remplir les options" data-position='top'
style="background-color: rgb(248,248,248); padding: 10px; border: 1px dotted #aaa;"
>
	<div id="store-missing-data" class="alert alert-danger" role="alert"></div>
	
	<div class="yiipanel-ChromaLuxe">
	<?= Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [

		        'chroma_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory(ItemCategory::CHROMALUXE_TYPE),
		            'columnOptions' => ['colspan' => 4],
					'options' => ['inline'=>true, 'class' => 'ItemChromaLuxe' ],
				],
		        'price_chroma' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemChromaLuxe'],
				],
			],
		])
	?>		
	</div>
	
	<div class="yiipanel-Tirage">
	<?= Html::activeHiddenInput($detail, 'tirage_factor') ?>	
	<?= Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [				
		        'tirage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::TIRAGE, true) + Item::getListForCategory(ItemCategory::CANVAS),
					'options' => ['class' => 'form-control ItemTirage ItemCanvas'],
		            'columnOptions' => ['colspan' => 3],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemTirage ItemCanvas']
				],
		        'tirage_factor_virgule' => [
					'type' => Form::INPUT_TEXT,
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory(ItemCategory::TIRAGE_PARAM),
					'options' => ['inline'=>true, 'class' => 'ItemTirage'],
		            'columnOptions' => ['colspan' => 5],
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
					'options' => ['class' => 'form-control ItemCanvas'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_chassis' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemCanvas']
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
					'options' => ['class' => 'form-control ItemTirage'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_support' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemTirage']
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
					'options' => ['class' => 'form-control ItemTirage'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_protection' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemTirage']
				],
			],
		]).
		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'filmuv_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 4],
					'options' => ['class' => 'ItemTirage'],
				],			
		        'price_filmuv' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemTirage']
				],
			],
		])
	?>	
	</div>
	
	<div class="yiipanel-Divers">		
	<?= Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 6,
		    'attributes' => [
		        'free_item_libelle' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['class' => 'form-control '. 'ItemDivers'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'free_item_price_htva' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> MaskedInput::className(),
					'options' => [
						'clientOptions' => [
					        'alias' =>  'decimal',
							'radixPoint' => ",",
					        'autoGroup' => false
						],
						'class' => 'form-control ItemDivers',
					],	
				],
		        'free_item_vat' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> MaskedInput::className(),
					'options' => [
						'clientOptions' => [
					        'alias' =>  'decimal',
							'radixPoint' => ",",
					        'autoGroup' => false
						], 
						'class' => 'form-control ItemDivers',
					],	
					'fieldConfig' => ['addon' => ['append' => ['content'=>'%']]],
				],
			],
		])
	?>	
	</div>
	
	<div class="yiipanel-Common">	
	<?= Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'corner_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 5/*4*/],
					'options' => ['class' => 'ItemChromaLuxe ItemTirage'],
				],			
		        'frame_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::FRAME, true),
					'options' => ['class' => 'form-control ItemChromaLuxe ItemTirage'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_frame' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemChromaLuxe ItemTirage'],
				],

		        'montage_bool' => [
					'type' => Form::INPUT_CHECKBOX,
					'options' => ['class' => 'ItemChromaLuxe ItemTirage'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_montage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemChromaLuxe ItemTirage'],
				],

		        'renfort_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory(ItemCategory::RENFORT, true),
					'options' => ['class' => 'form-control ItemChromaLuxe ItemTirage'],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_renfort' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control ItemChromaLuxe ItemTirage'],
				],
			],
		])
	?>
	</div>

</div>
<?= $this->render('_js_load_data') ?>