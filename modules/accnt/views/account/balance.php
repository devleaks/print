<?php

use app\models\Account;
use app\models\Payment;
use kartik\builder\Form;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;

$this->title = Yii::t('store', 'Customer {0}', [$client->niceName()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [in_array($role, ['manager', 'admin']) ? '/store' : '/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin();
	
	$captureForm = Form::widget([
	    'model' => $capture,
	    'form' => $form,
	    'columns' => 5,
	    'attributes' => [
	        'amount' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> MaskedInput::className(),
				'options' => ['clientOptions' => [
				        'alias' =>  'decimal',
							'radixPoint' => ",",
				        'groupSeparator' => '',
				        'autoGroup' => false
					],

				],	
			],
	        'date' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> DatePicker::classname(),
				'options' => ['pluginOptions' => [
	                'format' => 'yyyy-mm-dd',
	                'todayHighlight' => true
	            	],
					'options' => ['data-intro' => "Vous devez mentionner une date de versement."],
				],
			],
	        'method' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Payment::getPaymentMethods(),
			],
		],
	]).
	Html::activeHiddenInput($capture, 'client_id').
	Html::submitButton('<i class="glyphicon glyphicon-book"></i> '.Yii::t('store', 'Add Payment'),
							['class' => 'btn btn-primary'])
   ;
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Yii::t('store', 'Unbalanced Orders').'</h3>',
	        'before'=> '',
	        'after'=> $captureForm,
	        'showFooter'=>false
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	        [
	            'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->document ? $model->document->name : '';
	            },
			],
			[
	            'label' => Yii::t('store', 'Amount'),
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
				'pageSummary' => true
			],
			[
	            'label' => Yii::t('store', 'Balance'),
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
	            'value' => function ($model, $key, $index, $widget) {
                    return Account::getBalance($model->client_id, $model->created_at);
	            },
				'noWrap' => true,
			],
			[
           		'attribute' => 'note',
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->note ? $model->note : '';
	            },
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
	            'attribute' => 'status',
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
//			[
//				'class' => 'kartik\grid\ActionColumn',
//			 	'template' => '{update} {delete}'
//			],
			[
        		'class' => '\kartik\grid\CheckboxColumn'
			],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
