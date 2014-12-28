<?php

use app\models\Document;
use app\models\Parameter;
use kartik\detail\DetailView;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use kartik\widgets\SwitchInput;
//use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */

Icon::map($this);

$client = $model->getClient()->one();
?>

<div class="order-form">
	
        <div class="row">

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
 			                'value'=> $model->parent_id ? Html::a($model->parent->name, Url::to(['view', 'id' => $model->parent_id])) : ''
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
							'options' => ['readonly' => true]
			            ],
			            [
			                'attribute'=>'note',
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
				<?= $this->render('_header_client', ['client' => $client]) ?>
				</div>
			</div>

		</div>

		<p></p>

        <div class="row">
            <div class="col-lg-12">
				<div>
				<?= $model->getActions('btn', false) ?>
				<?= $this->render('_sendmail', ['model' => $model]) /** modal */ ?>
				<?php
				 	if(in_array($model->document_type, [Document::TYPE_ORDER,Document::TYPE_BILL,Document::TYPE_TICKET,Document::TYPE_CREDIT]))
						echo $this->render('_pay', ['model' => $model]); /** modal */
				?>
				</div>
			</div>
		</div>

</div>