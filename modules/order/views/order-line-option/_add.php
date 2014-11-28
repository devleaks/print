<?php

//use kartik\widgets\FileInput;
//use yii\widgets\ActiveForm;
use app\models\Item;
use app\models\OrderLineDetail;
use app\models\Parameter;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */
/* @var $form yii\widgets\ActiveForm */
$order_line_option = new OrderLineOption();
$order_line_option->order_line_id = $order_line->id;
$count = 0;
$attributes = [];
foreach(ItemOption::find()->where(['item_id'=>831]))->orderBy('position')->each() as $io) {
	$option = $io->option;
	$order_line_option->option_id = $option->id;

	// we first define a dummy param to keep hidden fields
	$attributes['dummy[]'] = [
		'type'=> Form::INPUT_RAW,
	    'form' => $form,
	    'columns' => 5,
		'value' =>	Html::activeHiddenInput($order_line_option.'[]', 'order_line_id').
					 Html::activeHiddenInput($order_line_option.'[]', 'option_id').
					'Hiddend fields set!',
	];
	
	switch($option->option_type) {
		case Option::TYPE_BOOLEAN:
			$type = Form::INPUT_CHECKBOX;
			$items = null;
			break;
		case Option::TYPE_RADIO:
			$type = Form::INPUT_RADIO_LIST;
			break;
		case Option::TYPE_BOOLEAN:
			$type = Form::INPUT_DROPDOWN_LIST;
			break;
	}

	$attributes['item_id[]'] => [
		'type' => $type,
		'items' => $items,
        'columnOptions' => ['colspan' => 4],
		'options' => ['class' => 'form-control'],
	];
	
	$attributes['item_price[]'] => [
		'type' => Form::INPUT_TEXT,
		'options' => ['readonly' => true, 'class' => 'form-control'],
	];
	
}

?>
<div class="order-line-options">

    <h4><?= Yii::t('store', 'Special Item') ?></h4>

	<h4 id="special-item-name">SPECIAL ITEM NAME</h4>

    <div>
	
	<div id="store-missing-data" class="alert alert-danger" role="alert">
	</div>
	
	<?= Form::widget([
				    'model' => $order_line_option,
				    'form' => $form,
				    'columns' => 5,
				    'attributes' => $attributes,
	]) ?>

	</div>

</div>