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

$this->title = Yii::t('store', 'Extractions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
		'toolbar' => [
			'{export}',
    	],
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Bills for Transfer').'</h3>',
	        'before'=> '',
	        'after'=> Html::label(Yii::t('store', 'Selection')).' : '.
    			Html::button('<i class="glyphicon glyphicon-book"></i> '.Yii::t('store', 'Extract'),
							['class' => 'btn btn-primary actionButton', 'data-action' => Bill::ACTION_EXTRACT])
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
        		'class' => '\kartik\grid\CheckboxColumn'
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
		url: "<?= Url::to(['/accnt/extraction/bulk-action']) ?>",
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
