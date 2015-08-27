<?php

use app\models\Document;
use app\models\Parameter;
use app\models\User;
use app\widgets\DocumentActionColumn;

use kartik\detail\DetailView;
use kartik\icons\Icon;
use kartik\widgets\SwitchInput;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
//use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */

Icon::map($this);

?>

<div class="order-form">
	
        <div class="row">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Yii::t('store',
												   ($model->document_type == Document::TYPE_ORDER && $model->bom_bool) ? Document::TYPE_BOM : $model->document_type
												  ) . ' # ' . $model->name	?></h3>
					<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
				</div>
				<div class="panel-body">
			
			
            <div class="col-lg-6">
				<div>
			    <?= DetailView::widget([
			        'model' => $model,
					'buttons1' => $model->canModify() ? '{update}' : '',
					'panel'=>[
				        'heading' => '<h4>'.Yii::t('store',
												   ($model->document_type == Document::TYPE_ORDER && $model->bom_bool) ? Document::TYPE_BOM : $model->document_type
												  ) . ' # ' . $model->name.'</h4>',
				        'type'=> $model->getStatusColor(),
				    ],
					'formOptions' => [
						'action' => Url::to(['/order/document/live-update', 'id'=>$model->id]),
					],
					'labelColOptions' => ['style' => 'width: 30%'],
			        'attributes' => [
			            //'id',
			            //'client_id',
			            [
			                'attribute'=>'parent_id',
			                'label'=>Yii::t('store','Related'),
 			                'value'=> $model->parent_id ? Html::a($model->parent->name, Url::to(['/order/document/view', 'id' => $model->parent_id])) : ''
							//.Html::activeHiddenInput($model, 'id')
							,
							'format' => 'raw',
							'options' => ['readonly' => true]
			            ],
			            [
			                'attribute'=>'due_date',
			                'label'=>Yii::t('store','Livraison'),
							'format' => 'date',
							'type' => '\kartik\date\DatePicker',
							'widgetOptions' => ['pluginOptions' => [
				                'format' => 'yyyy-mm-dd',
				                'todayHighlight' => true
				            ]]
			            ],
			            [
			                'attribute'=>'created_at',
							'format' => 'datetime',
							'value' => new DateTime($model->created_at),
							'options' => ['readonly' => true]
			            ],
			            [
			                'attribute'=>'updated_at',
							'format' => 'datetime',
							'value' => new DateTime($model->updated_at),
							'options' => ['readonly' => true]
			            ],
			            [
			                'attribute'=>'bom_bool',
			                'label'=> Yii::t('store','Bon de livraison'),
							'format' => 'raw',
							'value' => $model->bom_bool
											? '<span class="label label-success">'.Yii::t('store','Yes').'</span>'
											: '<span class="label label-danger">' .Yii::t('store','No') .'</span>',
							'type' => DetailView::INPUT_SWITCH,
							'widgetOptions' => ['pluginOptions' => [
								'onText' => Yii::t('store', 'Yes'),
								'offText' =>  Yii::t('store', 'No'),
							]]
			            ],
			            [
			                'attribute'=>'vat_bool',
			                'label'=> Yii::t('store','Pas de TVA'),
							'format' => 'raw',
							'value' => $model->vat_bool
											? '<span class="label label-success">'.Yii::t('store','Yes').'</span>'
											: '<span class="label label-danger">' .Yii::t('store','No') .'</span>',
							'type' => DetailView::INPUT_SWITCH,
							'widgetOptions' => ['pluginOptions' => [
								'onText' => Yii::t('store', 'Yes'),
								'offText' =>  Yii::t('store', 'No'),
							]]
			            ],
			            [
			                'attribute'=>'legal',
			                'label'=> Yii::t('store','Mention légale'),
							'format' => 'raw',
							'value' => $model->legal
											? Parameter::getMLText('legal', $model->legal)
											: '',
							'type' => DetailView::INPUT_DROPDOWN_LIST,
							'items' => [''=>''] + Parameter::getSelectList('legal', 'value_text'),
							'visible' => $model->vat_bool
			            ],
			            [
			                'attribute'=>'price_tvac',
			                'label'=> Yii::t('store','Solde'),
			                'value'=> $model->getBalance(),
							'format' => 'currency',
							'options' => ['readonly' => true]
			            ],
			            [
			                'attribute'=>'status',
			                'label'=> Yii::t('store','Status'),
			                'value'=> $model->getStatusLabel(true),
							'format' => 'raw',
							'type' => DetailView::INPUT_DROPDOWN_LIST,
							'items' => [''=>''] + Document::getStatuses(),
							'options' => ['readonly' => !User::hasRole(['manager', 'admin'])]
			            ],
			            [
			                'attribute'=>'reference_client',
			            ],
			            [
			                'attribute'=>'note',
			                'value'=> '<span class="rednote">'.$model->note.'</span>',
							'format' => 'raw',
			            ],
			            [
			                'attribute'=>'email',
			            ],
			            [
			                'attribute'=>'reference',
							'options' => ['readonly' => true]
			            ],
			        ],
			    ]) ?>
				</div>
				<div>
					<?php if ($work = $model->getWorks()->one()) echo $work->getTaskIcons(true, true, true); ?>
				</div>
			</div>

            <div class="col-lg-6">
				<div>
				<?= $this->render('_header_client', ['client' => $model->getClient()->one()]) ?>
				</div>
			</div>

				</div><!--panel-body-->
		</div><!--panel-->

		</div><!--row-->

		<p></p>

        <div class="row">
            <div class="col-lg-12">
				<div>
				<?php
					$ab = new DocumentActionColumn([
						'template' => $model->getActions(),
						'baseClass' => 'btn',
						'buttonTemplate' => '{icon} {text}',
					]);
					echo $ab->getButtons($model);
				 	//echo $model->getActions('btn', false);
					echo $this->render('_sendmail', ['model' => $model]); /** modal */
				 	if(in_array($model->document_type, [Document::TYPE_BID,Document::TYPE_ORDER,Document::TYPE_BILL,Document::TYPE_TICKET,Document::TYPE_REFUND,Document::TYPE_CREDIT])
					  && $model->status != Document::STATUS_CANCELLED )
						echo $this->render('_pay', ['model' => $model]); /** modal */
				?>
				</div>
			</div>
		</div>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PANEL'); ?>
jQuery(function ($) {
    $('.panel-heading span.clickable').on("click", function (e) {
        if ($(this).hasClass('panel-collapsed')) {
            // expand the panel
            $(this).parents('.panel').find('.panel-body').slideDown();
            $(this).removeClass('panel-collapsed');
            $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
        else {
            // collapse the panel
            $(this).parents('.panel').find('.panel-body').slideUp();
            $(this).addClass('panel-collapsed');
            $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        }
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PANEL'], yii\web\View::POS_END);
