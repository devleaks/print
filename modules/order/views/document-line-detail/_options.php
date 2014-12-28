<?php

use app\models\DocumentLineDetail;
use app\models\Item;
use app\models\Parameter;
use kartik\builder\Form;
use sjaakp\bandoneon\Bandoneon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
/* @var $form yii\widgets\ActiveForm */

$detail = new DocumentLineDetail();
$detail->document_line_id = $model->id;

$chroma_item  = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE]);
$fineart_item = Item::findOne(['reference'=>Item::TYPE_FINEARTS]);
$free_item    = Item::findOne(['reference'=>Item::TYPE_FREE]);
$class_prefix = 'item';

if($detail->free_item_vat == '') $detail->free_item_vat = 21;
?>
<div class="document-line-options">

	<div id="store-missing-data" class="alert alert-danger" role="alert"></div>	

	<?= Tabs::widget([
		    'items' => [
		        [
		            'label' => Yii::t('store', 'Special Item'),
		            'content' => '',
					'active' => true,
				],
		        [ /** ********************************************* */
		            'label' => 'ChromaLuxe',
					'headerOptions' => ['class' => 'order-option',
								  'data-item_id' => $chroma_item->id,
								  'data-item_name' => $chroma_item->libelle_long,
								  'data-item_vat' => $chroma_item->taux_de_tva,
					],
		            'content' => Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [

		        'chroma_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory('ChromaType'),
		            'columnOptions' => ['colspan' => 4],
					'options' => ['inline'=>true, 'class' => $class_prefix . $chroma_item->id ],
				],
		        'price_chroma' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
				],

		        'corner_bool' => [
					'type' => Form::INPUT_CHECKBOX,
		            'columnOptions' => ['colspan' => 5/*4*/],
					'options' => ['class' => $class_prefix . $chroma_item->id],
				],			
				/*			
		        'price_corner' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => $class_prefix . $chroma_item->id],
				],*/
		        'frame_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Cadre', true),
					'options' => ['class' => 'form-control '.$class_prefix . $chroma_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_frame' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
				],

		        'montage_bool' => [
					'type' => Form::INPUT_CHECKBOX,
					'options' => ['class' => $class_prefix . $chroma_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_montage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
				],

		        'renfort_bool' => [
					'type' => Form::INPUT_CHECKBOX,
					'options' => ['class' => $class_prefix . $chroma_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_renfort' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $chroma_item->id],
				],
			],
		]),
		        ],
		        [ /** ********************************************* */
		            'label' => Yii::t('store', 'Fine Art'),
					'headerOptions' => ['class' => 'order-option',
								  'data-item_id' => $fineart_item->id,
								  'data-item_name' => $fineart_item->libelle_long,
								  'data-item_vat' => $fineart_item->taux_de_tva,
					],
		            'content' => Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [				
		        'tirage_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Tirage', true),
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_tirage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
				],
		        'finish_id' => [
					'type' => Form::INPUT_RADIO_LIST,
					'items' => Item::getListForCategory('Finition'),
					'options' => ['inline'=>true, 'class' => $class_prefix . $fineart_item->id],
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
		        'chassis_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Chassis', true),
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_chassis' => [
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
		        'support_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Support', true),
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_support' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
				],
			],
		]).
/*		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [	
		        'frame_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Cadre', true),
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_frame' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id],
				],

		        'montage_bool' => [
					'type' => Form::INPUT_CHECKBOX,
					'options' => ['class' => $class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_montage' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id],
				],
			],
		]).
*/		Form::widget([
		    'model' => $detail,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'protection_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Item::getListForCategory('Vernis de protection', true),
					'options' => ['class' => 'form-control '.$class_prefix . $fineart_item->id],
		            'columnOptions' => ['colspan' => 4],
				],
		        'price_protection' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['readonly' => true, 'class' => 'form-control '.$class_prefix . $fineart_item->id]
				],
			],
		]).
		'',
		        ],
		        [ /** ********************************************* */
		            'label' => Yii::t('store', 'Free Item'),
					'headerOptions' => ['class' => 'order-option',
								  'data-item_id' => $free_item->id,
								  'data-item_name' => $free_item->libelle_long,
								  'data-item_vat' => $free_item->taux_de_tva,
					],
		            'content' => Form::widget([
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
					'widgetClass'=> MaskedInput::className(),
					'options' => ['clientOptions' => [
					        'alias' =>  'decimal',
							'radixPoint' => ",",
					        'groupSeparator' => '',
					        'autoGroup' => false
						],
						
					],	
				],
		        'free_item_vat' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> MaskedInput::className(),
					'options' => ['clientOptions' => [
					        'alias' =>  'decimal',
							'radixPoint' => ",",
					        'groupSeparator' => '',
					        'autoGroup' => false
						], 
					],	
					'fieldConfig' => ['addon' => ['append' => ['content'=>'%']]],
				],
			],
		]),
		        ],
		    ],
		]);
	?>

</div>
<?= $this->render('_js_load_data') ?>