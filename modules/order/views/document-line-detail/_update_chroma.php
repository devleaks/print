<?php

//use kartik\widgets\FileInput;
//use yii\widgets\ActiveForm;
use app\models\DocumentLineDetail;
use app\models\Item;
use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use sjaakp\bandoneon\Bandoneon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="document-line-update-options-chromaluxe">

	<h4><span style="color: #eea236;">Chroma</span>Luxe</h4>

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
							'items' => Item::getListForCategory(ItemCategory::CHROMALUXE_TYPE),
				            'columnOptions' => ['colspan' => 4],
							'options' => ['inline'=>true],
						],
				        'price_chroma' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true]
						],

				        'corner_bool' => [
							'type' => Form::INPUT_CHECKBOX,
				            'columnOptions' => ['colspan' => 5/*4*/],
						],			
						/*			
				        'price_corner' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true]
						],*/
				        'frame_id' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => Item::getListForCategory(ItemCategory::FRAME, true),
				            'columnOptions' => ['colspan' => 4],
							'options' => ['class' => 'form-control'],
						],
				        'price_frame' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true]
						],

				        'montage_bool' => [
							'type' => Form::INPUT_CHECKBOX,
				            'columnOptions' => ['colspan' => 4],
						],
				        'price_montage' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true]
						],

				        'renfort_id' => [
							'type' => Form::INPUT_DROPDOWN_LIST,
							'items' => Item::getListForCategory(ItemCategory::RENFORT, true),
							'options' => ['class' => 'form-control'],
				            'columnOptions' => ['colspan' => 4],
						],
				        'price_renfort' => [
							'type' => Form::INPUT_TEXT,
							'options' => ['readonly' => true]
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
chroma_price();
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);
