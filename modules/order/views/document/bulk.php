<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use app\models\User;
use app\modules\order\controllers\DocumentController;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\GridViewPDF;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$buttons = Html::a(Yii::t('store', 'Bill To'), null, ['class' => 'btn btn-primary store-action', 'data-action' => DocumentController::ACTION_CONVERT]);

$this->title = Yii::t('store', Document::getTypeLabel('All Documents', true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [User::hasRole(['manager', 'admin']) ? '/store' : '/order', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => ['id' => 'documents'],
		'pjax' => true,
		'export' => [
		    'fontAwesome' => true,
		],
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Html::encode($this->title).'</h3>',
	        'before'=> false, // $buttons,
	        'after'=> false, // Html::submitButton(Yii::t('store', 'Partial BOM'), ['class' => 'btn btn-primary']),
			'footer' => ' ',
	    ],
		'exportConfig' => [
   			GridView::PDF => [
				'config' => [
		            'mode' => 'c',
		            'format' => 'A4-L',
           			],
			]
		],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        [
				'attribute' => 'document_type',
	            'label' => Yii::t('store', 'Type'),
				'filter' => Document::getDocumentTypes(),
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->document_type);
				}
			],
	        [
				'attribute' => 'bill_exists',
	            'label' => Yii::t('store', 'Bill'),
				'filter' => ['' => '', 'Y' => Yii::t('store', 'Yes'), 'N' => Yii::t('store', 'No') ],
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->document_type);
				}
			],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Référence'),
	            'value' => function ($model, $key, $index, $widget) {
                    return User::hasRole(['manager', 'admin']) ? 
							Html::a($model->name, Url::to(['/order/document/view', 'id' => $model->id]))
							 : $model->name;
	            },
				'format' => 'raw',
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
				'attribute' => 'price_tvac',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
	            'label' => Yii::t('store', 'Prepaid'),
				'format' => 'currency',
				'value' => function ($model, $key, $index, $widget) {
					return $model->getPrepaid();
				},
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
	            'label' => Yii::t('store', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
	        [
	            'label' => Yii::t('store', 'Created By'),
				'attribute' => 'created_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getCreatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
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
//	        [
//	            'label' => Yii::t('store', 'Actions'),
//	            'value' => function ($model, $key, $index, $widget) {
//							return $model->getActions('btn btn-xs', false, '{icon}');
//	            		},
//				'hAlign' => GridView::ALIGN_CENTER,
//	            'format' => 'raw',
//				'noWrap' => true,
//	        ],
	        [
				'class'	=> 'kartik\grid\CheckboxColumn',
	        ],
        ],
    ]); ?>

	<?= $buttons ?>
</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.store-action").click(function(e) {
	action = $(this).data('action');
	collected = $('#documents').yiiGridView('getSelectedRows');
	if(collected != '') {
		$.post(
		    "bulk-action", 
		    {
		        ids : collected,
				action : action
		    },
		    function () {
		        $.pjax.reload({container:'#documents-pjax'});
		    }
		);
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PJAXREG'], yii\web\View::POS_READY);