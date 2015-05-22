<?php

use app\models\Bill;
use app\models\Document;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\GridViewPDF;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', Document::getTypeLabel(Document::TYPE_BILL, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-index">

    <?= GridView::widget([
		'options' => ['id' => 'action-gridview'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Unpaid Bills').'</h3>',
	        'before'=> '',
	        'after'=> '',
			'afterOptions' => ['class'=>'kv-panel-after pull-right'],
	        'footer'=> ''
	    ],
		'panelHeadingTemplate' => '{heading}',
		'showPageSummary' => true,
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'client_name',
	            'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->client->nom;
				},
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
				'hAlign' => GridViewPDF::ALIGN_CENTER,
	        ],
			[
        		'class' => '\kartik\grid\CheckboxColumn',
				'rowSelectedClass' => Gridview::TYPE_INFO,
			],
        ],
    ]); ?>

	<div class="pull-right">
		
	<?php $form = ActiveForm::begin(['id' => 'store-action', 'action' => Url::to(['bulk-action'])]) ?>

    <?= Html::hiddenInput('action') ?>
    <?= Html::hiddenInput('selection') ?>

	<?= Html::label(Yii::t('store', 'Selection')).' : '.
    			Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '.Yii::t('store', 'Add Payment'),
							['class' => 'btn btn-primary actionButton', 'data-action' => Bill::ACTION_PAYMENT_RECEIVED]).' '.
    			Html::submitButton('<i class="glyphicon glyphicon-envelope"></i> '.Yii::t('store', 'Send Reminder'),
							['class' => 'btn btn-warning actionButton', 'data-action' => Bill::ACTION_SEND_REMINDER])?>

    <?php ActiveForm::end(); ?>

	</div>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_SUBMIT_ACTION') ?>
$('.actionButton').click(function() {
	status = $(this).data('action');
	selected = $('#action-gridview').yiiGridView('getSelectedRows');
	$('input[name="action"]').val(status);
	$('input[name="selection"]').val(selected);
	$('#store-action').submit();
});
<?php $this->endBlock(); ?>
</script>
</div>
<?php
$this->registerJs($this->blocks['JS_SUBMIT_ACTION'], yii\web\View::POS_END);
