<?php

use app\models\Work;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkLine */
$order = $model->getOrderLine()->one()->getOrder()->one();
$order_line = $model->getOrderLine()->one();
$this->title = $model->getTask()->one()->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];

$this->params['breadcrumbs'][] = ['label' => $order->name, 'url' => ['/work/work/view', 'id' => $model->work_id]];
$this->params['breadcrumbs'][] = ['label' => $order_line->getItem()->one()->libelle_long, 'url' => ['/work/work/line', 'id' => $order_line->id]];

$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="work-line-view">

	<?= $this->render('_order_line', [ // there should only be one order line for this work line
	        'model' => $model->getOrderLine()->one(),
		])
	?>


	<div class="row">
	<div class="col-lg-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'work_id',
            [
                'label'=>Yii::t('store','Tâche'),
                'value'=> $model->task->name,
			],
            'note',
            [
                'attribute'=>'status',
                'label'=>Yii::t('store','Status'),
                'value'=> Yii::t('store',$model->status),
			],
            [
                'attribute'=>'status',
                'label'=>Yii::t('store','Updated'),
                'value'=> $model->getUpdatedBy()->one()->username . ' ' . Yii::t('store', 'at') . ' ' . $model->updated_at,
			],
//          [
//              'attribute'=>'status',
//              'label'=>Yii::t('store','Created'),
//              'value'=> $model->getCreatedBy()->one()->username . ' ' . Yii::t('store', 'at') . ' ' . $model->created_at,
//			],
            // 'order_line_id',
        ],
    ]) ?>

	</div>
	</div>

	<div class="row work-line-form">

		<div class="col-lg-12">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'note')->textInput() ?>

	    <?= Html::activeHiddenInput($model, 'status') ?>

	    <div class="form-group">
	        <?= Html::submitButton('<i class="glyphicon glyphicon-edit"></i> '.Yii::t('store', 'Add note'),
							['class' => 'btn btn-info']) ?>

	    	<?= Html::button('<i class="glyphicon glyphicon-inbox"></i> '.Yii::t('store', 'Take'),
							['class' => 'btn btn-primary set-workline-status', 'data-status' => Work::STATUS_BUSY]) ?>
	    	<?= Html::button('<i class="glyphicon glyphicon-ok-sign"></i> '.Yii::t('store', 'Done'),
							['class' => 'btn btn-success set-workline-status', 'data-status' => Work::STATUS_DONE]) ?>
	    	<?= Html::button('<i class="glyphicon glyphicon-warning-sign"></i> '.Yii::t('store', 'Warn'),
							['class' => 'btn btn-warning set-workline-status', 'data-status' => Work::STATUS_WARN]) ?>
	    	<?= Html::button('<i class="glyphicon glyphicon-remove"></i> '.Yii::t('store', 'Redo'),
							['class' => 'btn btn-danger set-workline-status', 'data-status' => Work::STATUS_TODO]) ?>

	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_SUBMIT_STATUS') ?>
$('.set-workline-status').click(function() {
	status = $(this).data('status');
	console.log(status);
	$('#workline-status').val(status);
	$('#w1').submit();
});
<?php $this->endBlock(); ?>
</script>
</div>
<?php
$this->registerJs($this->blocks['JS_SUBMIT_STATUS'], yii\web\View::POS_END);
