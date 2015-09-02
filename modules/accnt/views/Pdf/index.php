<?php

use app\models\Pdf;

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PdfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'PDF Documents to Print');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdf-index">

    <?= GridView::widget([
		'options' => ['id' => 'action-gridview'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Yii::t('store', $this->title).'</h3>',
	        'before'=> ' ',
	        'after'=> Html::button(Yii::t('store', 'Print'), ['class' => 'btn btn-primary actionButton', 'data-action' => Pdf::ACTION_PRINT]).' '.
					  Html::button(Yii::t('store', 'Delete'), ['class' => 'btn btn-danger actionButton', 'data-action' => Pdf::ACTION_DELETE]),
	    ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

	        [
	            'label' => Yii::t('store', 'Document Type'),
				'attribute' => 'document_type',
				'filter' => ArrayHelper::map(Pdf::find()->select(['document_type', 'document_type'])->distinct()->asArray()->all(), 'document_type', 'document_type'),
				'value' => function ($model, $key, $index, $widget) {
					return Yii::t('store', $model->getDocumentType());
				}
			],
	        [
				'attribute' => 'document_id',
				'value' => 'document.name'
			],
	        [
				'attribute' => 'client_name',
				'value' => function ($model, $key, $index, $widget) {
					if($cli = $model->getClient()->one() )
					return $cli->nom.($cli->email? ' ('.$cli->email.')' : '');
				}
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
	            'label' => Yii::t('store', 'Sent At'),
				'attribute' => 'sent_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				}
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
	            'buttons' => [
	                'view' => function ($url, $model) {
	                    return file_exists($model->getFilepath()) ?
								Html::a('<i class="glyphicon glyphicon-eye-open"></i>',
									Url::to(['view', 'id' => $model->id]),
									[
			                        	'title' => Yii::t('store', 'View'),
										'target' => '_blank'
			                    	])
								:
								''
								;
	                },
	            ]
			],
			[
				'class' => 'kartik\grid\CheckboxColumn',
            ]
        ],
    ]); ?>

	<div class="pull-right">
		
	<?php $form = ActiveForm::begin(['id' => 'store-action', 'action' => Url::to(['bulk-action'])]) ?>

    <?= Html::hiddenInput('action') ?>
    <?= Html::hiddenInput('selection') ?>

    <?php ActiveForm::end(); ?>

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
