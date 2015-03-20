<?php

use app\models\Work;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkLine */
$order = $model->getDocumentLine()->one()->getDocument()->one();
$order_line = $model->getDocumentLine()->one();
$this->title = $model->getTask()->one()->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];

$this->params['breadcrumbs'][] = ['label' => $order->name, 'url' => ['/work/work/view', 'id' => $model->work_id]];
$this->params['breadcrumbs'][] = ['label' => $order_line->getItem()->one()->libelle_long, 'url' => ['/work/work/line', 'id' => $order_line->id]];

$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="work-line-view">

	<?= $this->render('_order_line', [ // there should only be one order line for this work line
	        'model' => $model->getDocumentLine()->one(),
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
            [
                'attribute'=>'status',
                'label'=>Yii::t('store','Status'),
                'value'=> $model->getStatusLabel(),
				'format' => 'raw'
			],
            [
                'attribute'=>'updated_by',
                'label'=>Yii::t('store','Updated'),
                'value'=> $model->getUpdatedBy()->one()->username . ' ' . Yii::t('store', 'at') . ' ' . Yii::$app->formatter->asDateTime($model->updated_at),
			],
            [
                'attribute'=>'note',
                'value'=> $model->note ? '<span class="rednote">'.$model->note.'</span>' : '',
				'format' => 'raw',
			],
//          [
//              'attribute'=>'status',
//              'label'=>Yii::t('store','Created'),
//              'value'=> $model->getCreatedBy()->one()->username . ' ' . Yii::t('store', 'at') . ' ' . $model->created_at,
//			],
            // 'document_line_id',
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

			<?= Html::a('<i class="glyphicon glyphicon-tag"></i> '.Yii::t('store', 'Print Labels'),
							Url::to(['/order/document-line/label', 'id' => $model->document_line_id]), [
	                        'class' => 'btn btn-info',
	                        'title' => Yii::t('store', 'Labels'),
							'target' => '_blank',
	                    ]) ?>

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
