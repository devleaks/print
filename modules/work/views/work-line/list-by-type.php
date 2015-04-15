<?php
use app\models\Task;
use app\models\User;
use app\models\Work;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Tasks').' « '.$task->name.' »';
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this);
$extra_cmds = ' ';
if($task->name == 'Commande Encadrement') {
	$extra_cmds .= Html::button('<i class="glyphicon glyphicon-shopping-cart"></i> '.Yii::t('store', 'Order Frames'),
							['class' => 'btn btn-primary  actionButton', 'data-status' => 'FRAMES']);
} else if($task->name == 'RenfortsXX') {
	$extra_cmds .= Html::a('<i class="glyphicon glyphicon-th"></i> '.Yii::t('store', 'Compute Cuts'),
							Url::to(['to-cut']),
							['class' => 'btn btn-primary  actionButton', 'data-status' => 'CUTS']);
}
?>
<div class="work-line-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'export' => [
		     'fontAwesome' => true
		 ],
		'toolbar' => [
			'{export}',
			'{toggleData}'
    	],
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Icon::show($task->icon).' '.Yii::t('store', 'Tasks').' <em>« '.$task->name.' »</em>'.'</h3>',
	        'before'=> ' ',
	        'after'=> Html::label(Yii::t('store', 'Selection')).' : '.
    			Html::button('<i class="glyphicon glyphicon-inbox"></i> '.Yii::t('store', 'Take'),
							['class' => 'btn btn-primary actionButton', 'data-status' => Work::STATUS_BUSY])
				.' '.
    			Html::button('<i class="glyphicon glyphicon-ok"></i> '.Yii::t('store', 'Complete'),
							['class' => 'btn btn-success actionButton', 'data-status' => Work::STATUS_DONE])
				.' '.
    			Html::button('<i class="glyphicon glyphicon-remove"></i> '.Yii::t('store', 'Redo'),
							['class' => 'btn btn-danger  actionButton', 'data-status' => Work::STATUS_TODO])
				.$extra_cmds,
	        'showFooter'=>false
	    ],
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],

            [
				'attribute' => 'order_name',
                'label'=>Yii::t('store','Order'),
	            'value'=> function ($model, $key, $index, $widget) {
					$who = ''; // '<br/>'.$model->getWork()->one()->getDocument()->one()->getCreatedBy()->one()->username;
					return (in_array(Yii::$app->user->identity->role, ['manager', 'admin']) ?
							Html::a($model->getWork()->one()->getDocument()->one()->name,
								Url::to(['/order/document/view', 'id' => $model->getWork()->one()->getDocument()->one()->id]))
							: $model->getWork()->one()->getDocument()->one()->name).$who ;
				},
            	'format' => 'raw',
				'noWrap' => true,
            ],
			[
                'label'=>Yii::t('store','Created By'),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->work->document->createdBy->username ;
				},
            	'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
            ],
			[
				'attribute' => 'client_name',
                'label'=>Yii::t('store','Client'),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->getWork()->one()->getDocument()->one()->getClient()->one()->nom ;
				},
            	'format' => 'raw',
            ],
            [
				'attribute' => 'due_date',
                'label'=>Yii::t('store','Due Date'),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->due_date;
				},
            	'format' => 'date',
            ],
            [
				'attribute' => 'status',
                'label'=>Yii::t('store','Status'),
	            'filter' => Work::getStatuses(),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->getStatusLabel();
				},
				'hAlign' => GridView::ALIGN_CENTER,
            	'format' => 'raw',
            ],
//			[
//	            'label' => Yii::t('store', 'Last Update'),
//				'attribute' => 'updated_at',
//				'format' => 'datetime',
//				'value' => function ($model, $key, $index, $widget) {
//					return new DateTime($model->updated_at);
//				}
//			],
//	        [
//				'attribute' => 'updated_by',
//	            'label' => Yii::t('store', 'Updated By'),
//				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
//	            'value' => function ($model, $key, $index, $widget) {
//					$user = $model->getUpdatedBy()->one();
//	                return $user ? $user->username : '?';
//	            },
//				'hAlign' => GridView::ALIGN_CENTER,
//	            'format' => 'raw',
//	        ],
	        [
				'attribute' => 'item_name',
	            'label' => Yii::t('store', 'Item'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->item->libelle_court;
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'quantity',
	            'label' => Yii::t('store', 'Quantity'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getDocumentLine()->one()->quantity;
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
	        [
	            'label' => Yii::t('store', 'Options'),
	            'value' => function ($model, $key, $index, $widget) {
					$det = $model->getDocumentLine()->one()->getDocumentLineDetails()->one();
	                return $det ? $det->getDescription() : Yii::t('store', 'None');
	            },
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'work_width',
	            'label' => Yii::t('store', 'Width'),
	            'value' => function ($model, $key, $index, $widget) {
					return $model->documentLine->work_width;
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'work_height',
	            'label' => Yii::t('store', 'Height'),
	            'value' => function ($model, $key, $index, $widget) {
					return $model->documentLine->work_height;
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
	        [
				'class' => '\kartik\grid\DataColumn',
				'label' => Yii::t('store', 'Picture'),
	            'value' => function ($model, $key, $index, $widget) {
					$pic = $model->getDocumentLine()->one()->getPictures()->one();
					$ip  = $model->getDocumentLine()->one()->getPlaceholder();
					return $pic ? Html::img(Url::to($pic->getThumbnailUrl(), true)) : $ip;
					// placeholder: Yii::$app->homeUrl . 'assets/i/thumbnail.png';
                },
				'hAlign' => GridView::ALIGN_CENTER,
            	'format' => 'raw',
	        ],
	        [
	            'class' => 'kartik\grid\ActionColumn',
				'noWrap' => true,
	            'template' => '{detail} {take} {done} {undo}',
	            'buttons' => [
	                'detail' => function ($url, $model) {
						$url = Url::to(['work-line/detail', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'class' => 'btn btn-xs btn-info',
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
	                'take' => function ($url, $model) {
						$url = Url::to(['work-line/take', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-inbox"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'Take'),
	                    ]);
	                },
	                'done' => function ($url, $model) {
						$url = Url::to(['work-line/done', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $url, [
	                        'class' => 'btn btn-xs btn-success',
	                        'title' => Yii::t('store', 'Done'),
	                        'data-confirm' => Yii::t('store', 'Did you terminate this task?'),
	                    ]);
	                },
	                'undo' => function ($url, $model) {
						$url = Url::to(['work-line/undo', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
	                        'class' => 'btn btn-xs btn-danger',
	                        'title' => Yii::t('store', 'Redo'),
	                        'data-confirm' => Yii::t('store', 'Do you want to UNDO/REDO this task?'),
	                    ]);
	                },
	            ]
	        ],
			[
        		'class' => '\kartik\grid\CheckboxColumn'
			],
       	],
     ]); ?>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_SUBMIT_STATUS') ?>
$('.actionButton').click(function () {
	var status = $(this).data('status');
	console.log('doing for '+status);
	var keys = $('#w0').yiiGridView('getSelectedRows');
	console.log('doing for '+keys);
	$.ajax({
		type: "POST",
		url: 'bulk-status',
		dataType: 'json',
		data: {
			"CaptureWorkStatus[keylist]": keys,
			"CaptureWorkStatus[status]": status
		},
		success: function(data) {
			alert('I did it! Processed checked rows.')
		},
	});
});
<?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['JS_SUBMIT_STATUS'], yii\web\View::POS_END);
