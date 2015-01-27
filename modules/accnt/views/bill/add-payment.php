<?php

use app\models\Bill;
use app\models\Document;
use app\models\Payment;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$capture->method = 'TRANSFER';

$this->title = Yii::t('store', Document::getTypeLabel(Document::TYPE_BILL, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-index">

	<?php $form = ActiveForm::begin(['action' => 'add-payment']);
	
	$captureForm = Form::widget([
	    'model' => $capture,
	    'form' => $form,
	    'columns' => 5,
	    'attributes' => [
	        'amount' => [
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
	        'client_id' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => $clients,
	            'columnOptions' => ['colspan' => 2],
			],
	        'note' => [
				'type' => Form::INPUT_TEXT,
	            'columnOptions' => ['colspan' => 5],
			],
		],
	]).
	Html::submitButton('<i class="glyphicon glyphicon-book"></i> '.Yii::t('store', 'Add Payment'), ['class' => 'btn btn-primary']);
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Unbalanced Orders').'</h3>',
	        'before'=> '',
	        'after'=> $captureForm,
	        'showFooter'=>false
	    ],
		'panelHeadingTemplate' => '{heading}',
		'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'client_name',
	            'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
							return Html::hiddenInput('selection[]', $model->id).$model->client->nom;
				},
				'format' => 'raw',
				'noWrap' => true,
			],
	        [
	            'label' => Yii::t('store', 'Client Email'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->client->email;
				}
			],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Order'),
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'price_tvac',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Prepaid'),
				'format' => 'currency',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getPrepaid();
				},
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'price_tvac',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getBalance();
				},
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				}
			],
			[
	            'label' => Yii::t('store', 'Delay'),
				'value' => function ($model, $key, $index, $widget) {
					$i = $model->getDelay('created', true);
					$color = ['success', 'info', 'warning', 'danger'];
					$icon = ['ok-sign', 'warning-sign', 'warning-sign', 'remove'];
					return '<span class="badge alert-'.$color[$i].'"><i class="glyphicon glyphicon-'.$icon[$i].'"></i> '.$model->getDelay('created').'</span>';
				},
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
			],
	        [
	            'label' => Yii::t('store', 'Status'),
	            'attribute' => 'status',
	            'filter' => Document::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getStatusLabel(true);
	            		},
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
	        [
				'class'	=> 'app\widgets\DocumentActionColumn',
				'noWrap' => true,
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>