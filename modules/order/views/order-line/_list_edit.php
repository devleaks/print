<?php

use app\models\Parameter;
use app\models\OrderLine;
use app\models\OrderLineSearch;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$contentBefore = $this->render('../order/_header_print', ['model' => $order]);
$contentAfter = $this->render('../order/_footer_print', ['model' => $order]);
?>
<div class="order-line-list">
<p></p>
    <?= TabularForm::widget([
			'dataProvider' => $dataProvider,
    		'form' => $form,
			//'filterModel' => $searchModel,
    		'gridSettings' => [
		        'panel' => [
		            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.Yii::t('store', 'Order Items').'</h3>',
					'after'=> Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
		        ],
	        ],
			'attributes' => [
				'item.libelle_court' => [
					'type' => TabularForm::INPUT_STATIC,
					//'value' => $model->item->libelle_court,
				],
				'work_width' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'work_height' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'note' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'quantity' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'unit_price' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'vat' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'price_htva' => [
					'type' => TabularForm::INPUT_STATIC,
				],
				'extra_type' => [
					'type' => TabularForm::INPUT_DROPDOWN_LIST,
					'items' => array_merge(["" => ""], Parameter::getSelectList('extra', 'value_text')),
				],
				'extra_amount' => [
					'type' => TabularForm::INPUT_TEXT,
				],
				'extra_htva' => [
					'type' => TabularForm::INPUT_STATIC,
				],
				'price_htva' => [
					'type' => TabularForm::INPUT_STATIC,
				],
				'price_tvac' => [
					'type' => TabularForm::INPUT_STATIC,
				],
				'image' => [
					'type' => TabularForm::INPUT_STATIC,
				],
			],
		]);
	?>

</div>
