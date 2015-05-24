<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* */
$this->title = Yii::t('store', 'Bill Bills of Materials');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

	
	<?php $form = ActiveForm::begin(['id' => 'boms-selection', 'action' => Url::to(['bill-boms']), 'method' => 'post']); ?>
	<?= Html::activeHiddenInput($capture, 'selection') ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Yii::t('store', 'Bills of Materials').'</h3>',
	        'before'=> ' ',
	        'after'=> Html::submitButton(Yii::t('store', 'Bill To'), ['class' => 'btn btn-primary actionButton']),
	    ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
				'attribute' => 'price_htva',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
				'attribute' => 'updated_at',
	            'label' => Yii::t('store', 'Last Update'),
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
				'class' => 'kartik\grid\CheckboxColumn',
			]
        ],
    ]); ?>

	<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_SUBMIT_STATUS') ?>
$('#boms-selection').submit(function () {
	var selection = $('#w0').yiiGridView('getSelectedRows');
	$("#captureselection-selection").val(selection);
	$(this).submit();
});
<?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['JS_SUBMIT_STATUS'], yii\web\View::POS_END);
