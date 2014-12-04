<?php

use app\models\Bill;
use app\models\Document;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($document_type))
	$document_type = Document::TYPE_BILL;

$this->title = Yii::t('store', Document::getTypeLabel($document_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Unpaid Bills').'</h3>',
	        'before'=> '',
	        'after'=> Html::label(Yii::t('store', 'Selection')).' : '.
    			Html::button('<i class="glyphicon glyphicon-inbox"></i> '.Yii::t('store', 'Payment Received'),
							['class' => 'btn btn-primary actionButton', 'data-action' => Bill::ACTION_PAYMENT_RECEIVED]).' '.
    			Html::button('<i class="glyphicon glyphicon-envelope"></i> '.Yii::t('store', 'Send Reminder'),
							['class' => 'btn btn-primary actionButton', 'data-action' => Bill::ACTION_SEND_REMINDER])
				,
	        'showFooter'=>false
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Référence'),
			],
	        [
				'attribute' => 'client_name',
	            'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->client->nom;
				}
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'price_tvac',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Prepaid'),
				'attribute' => 'prepaid',
				'format' => 'currency',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->prepaid ? $model->prepaid : 0;
				},
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Solde'),
				'attribute' => 'price_tvac',
	            'value' => function ($model, $key, $index, $widget) {
							return $model->price_tvac - $model->prepaid;
				},
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true,
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
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
	            'label' => Yii::t('store', 'Actions'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getActions('btn btn-xs', false, '{icon}');
	            		},
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
				'noWrap' => true,
	        ],
			[
        		'class' => '\kartik\grid\CheckboxColumn',
				'rowSelectedClass' => Gridview::TYPE_INFO,
			],
        ],
		'showPageSummary' => true,
    ]); ?>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_SUBMIT_STATUS') ?>
$('.actionButton').click(function () {
	var action = $(this).data('action');
	console.log('doing for '+action);
	var keys = $('#w0').yiiGridView('getSelectedRows');
	console.log('doing for '+keys);
	$.ajax({
		type: "POST",
		url: "<?= Url::to(['/accnt/bill/bulk-action']) ?>",
		dataType: 'json',
		data: {
			keylist: keys,
			action: action
		},
		success: function(data) {
			alert('I did it! Processed checked rows.');
		},
	});
});
<?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['JS_SUBMIT_STATUS'], yii\web\View::POS_END);
