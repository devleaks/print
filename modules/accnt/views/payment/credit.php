<?php

use app\models\Parameter;
use app\models\Payment;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $dataProvider app\data\dataProvider */

$this->title = Yii::t('store', 'Excess Payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="credit-index">
	
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = ActiveForm::begin(['action' => Url::to(['refund'])]);

		$captureForm = Form::widget([
		    'model' => $capture,
		    'form' => $form,
		    'columns' => 5,
		    'attributes' => [
		        'date' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=> DatePicker::classname(),
					'options' => ['pluginOptions' => [
		                'format' => 'yyyy-mm-dd',
		                'todayHighlight' => true
		            	],
						'options' => ['data-intro' => "Vous devez mentionner une date de versement."],
					],
				],
		        'method' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => Payment::getPaymentMethods(),
				],
		        'note' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
			],
		]);
		$captureForm .= Html::submitButton('– <i class="glyphicon glyphicon-euro"></i> '.Yii::t('store', 'Reimburse'), ['class' => 'btn btn-primary']);
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Excess Payments for Reimbursement').'</h3>',
	        'before'=> false,
	        'after'=> $captureForm,
	        'showFooter'=>false
	    ],
		'layout' => '{items}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'client.nom',
	        ],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
	        [
				'attribute' => 'payment_method',
				'value' => function($model, $key, $index, $widget) {
					return Parameter::getTextValue('payment', $model->payment_method);					
				}
	        ],
	        [
				'attribute' => 'note',
	        ],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				},
				'noWrap' => true,
			],
            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
