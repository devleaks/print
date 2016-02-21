<?php

use app\models\AccountingJournal;
use app\models\ItemCategory;
use app\models\Parameter;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
$model->status = $model->status == $model::STATUS_ACTIVE;

?>

<div class="item-form">

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
	]); ?>

	<?= Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 12,
			'attributes' => [
		        'reference' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'libelle_long' => [
					'label' => Html::label(Yii::t('store', 'Label')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 8],
				],
		        'libelle_court' => [
					'label' => Html::label(Yii::t('store', 'Short Label')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 4],
				],
		        'categorie' => [
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_de_vente' => [
					'label' => Html::label(Yii::t('store', 'Prix')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'taux_de_tva' => [
					'label' => Html::label(Yii::t('store', 'VAT')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 1],
				],
		        'commentaires' => [
					'label' => Html::label(Yii::t('store', 'Notes')),
					'type' => Form::INPUT_TEXTAREA,
		            'columnOptions' => ['colspan' => 12],
				],
		        'comptabilite' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(AccountingJournal::find()->asArray()->all(), 'code', 'name'),
		            'columnOptions' => ['colspan' => 2],
				],
		        'yii_category' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ItemCategory::getCategories(),
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_a' => [
					'label' => Html::label(Yii::t('store', 'Prix « A &times <i>x</i> »')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'prix_b' => [
					'label' => Html::label(Yii::t('store', 'Prix « + B »')),
					'type' => Form::INPUT_TEXT,
		            'columnOptions' => ['colspan' => 2],
				],
		        'status' => [
					'label' => Html::label(Yii::t('store', 'Status')).'<br>',
					'type' => Form::INPUT_WIDGET,
					'widgetClass'=>SwitchInput::className(),
					'options' => ['pluginOptions' => [
								'onText' => Yii::t('store', $model::STATUS_ACTIVE),
								'offText' =>  Yii::t('store', $model::STATUS_RETIRED),
						        'onColor' => 'success',
						        'offColor' => 'danger',
								'state' => $model->status == $model::STATUS_ACTIVE
					]],
		            'columnOptions' => ['colspan' => 2],
				],
			]
		])
	?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Yii::t('store','Older Data') ?></h3>
					<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
				</div>
				<div class="panel-body">
			
    <?= $form->field($model, 'fournisseur')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'reference_fournisseur')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'type_travaux_photos')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'type_numerique')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'conditionnement')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'prix_d_achat_de_reference')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'client')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'quantite')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'date_initiale')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'date_finale')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'identification')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'suivi_de_stock')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'reassort_possible')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'seuil_de_commande')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'site_internet')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'en_cours')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'stock')->textInput(['maxlength' => 20]) ?>

				</div>
			</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PANEL'); ?>
$('.panel-heading span.clickable').on("click", function (e) {
    if ($(this).hasClass('panel-collapsed')) {
        $(this).parents('.panel').find('.panel-body').slideDown();
        $(this).removeClass('panel-collapsed');
        $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    } else {
        $(this).parents('.panel').find('.panel-body').slideUp();
        $(this).addClass('panel-collapsed');
        $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    }
});
$('.panel-heading span.clickable').trigger("click");
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PANEL'], yii\web\View::POS_READY);
