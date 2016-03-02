<?php

use app\models\Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = Yii::t('store', 'Create '.ucfirst(strtolower($model->document_type)));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', ucfirst(strtolower($model->document_type).'s')), 'url' => [strtolower($model->document_type).'s']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Last Orders'), 'template' => "<li><span class='last-orders label label-info'>{link}</span></li>\n",];
?>
<div class="order-create">
	
	<div class="last-order-list" style='display:none'>
	<?= $this->render('recent') ?>
	</div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
		/** If create new doc, form is opened and closed here; there is a single form for the entire page */
		$form = ActiveForm::begin([
			'type'    => ActiveForm::TYPE_VERTICAL,
	        'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-compact'],
			'id' => 'documentline-form'
		]); ?>

	<?= $this->render('_header_form', [
			'model' => $model,
			'form' => $form,
		])
	?>

	<?php if(!in_array($model->document_type, [Document::TYPE_CREDIT,Document::TYPE_REFUND])): ?>
		<?= $this->render('../document-line/_list_add', [
				'order' => $model,
				'form' => $form,
			])
		?>
	<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add Item'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_LAST_ORDERS') ?>
$('.last-orders').click(function () {
	console.log('click');
	$('.last-order-list').toggle();
});
<?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['JS_LAST_ORDERS'], yii\web\View::POS_END);
