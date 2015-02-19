<?php
use app\models\Item;
use app\models\Task;
use app\models\User;
use app\models\Work;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Tasks').' « '.$task->name.' »';
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this);
?>
<div class="work-line-index">

	<?php $form = ActiveForm::begin(['action' => Url::to(['adjust-cuts'])]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading'=>'<h3 class="panel-title">'.Icon::show($task->icon).' '.Yii::t('store', 'Tasks').' <em>« '.$task->name.' »</em>'.'</h3>',
	        'before'=> ' ',
	        'after'=> Html::label(Yii::t('store', 'Selection')).' : '.
    			Html::submitButton('<i class="glyphicon glyphicon-screenshot"></i> '.Yii::t('store', 'Cut'),
							['class' => 'btn btn-primary actionButton'])
				,
	        'showFooter'=>false
	    ],
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],
            [
				'attribute' => 'order_name',
                'label'=>Yii::t('store','Order'),
	            'value'=> function ($model, $key, $index, $widget) {
					return in_array(Yii::$app->user->identity->role, ['manager', 'admin']) ?
							Html::a($model->getWork()->one()->getDocument()->one()->name,
								Url::to(['/order/document/view', 'id' => $model->getWork()->one()->getDocument()->one()->id]))
							: $model->getWork()->one()->getDocument()->one()->name ;
				},
            	'format' => 'raw',
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
//            [
//				'attribute' => 'status',
//                'label'=>Yii::t('store','Status'),
//	            'filter' => Work::getStatuses(),
//	            'value'=> function ($model, $key, $index, $widget) {
//					return $model->getStatusLabel();
//				},
//				'hAlign' => GridView::ALIGN_CENTER,
//            	'format' => 'raw',
//            ],
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
//	        [
//				'attribute' => 'item_name',
//	            'label' => Yii::t('store', 'Item'),
//	            'value' => function ($model, $key, $index, $widget) {
//	                return $model->item->libelle_court;
//	            },
//				'hAlign' => GridView::ALIGN_CENTER,
//	            'format' => 'raw',
//	        ],
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
	            'label' => Yii::t('store', 'Support'),
	            'value' => function ($model, $key, $index, $widget) {
					$dl = $model->getDocumentLine()->one();
					if($dl->isChromaLuxe())
						$ret = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE])->libelle_long;
					else if($sup = $dl->getSupport())
						$ret = $sup->libelle_long;
					else
						$ret = Yii::t('store', 'None');
	                return $ret;
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
					return $pic ? Html::img(Url::to($pic->getThumbnailUrl(), true)) : '';
					// placeholder: Yii::$app->homeUrl . 'assets/i/thumbnail.png';
                },
				'hAlign' => GridView::ALIGN_CENTER,
            	'format' => 'raw',
	        ],
			[
        		'class' => '\kartik\grid\CheckboxColumn'
			],
       	],
     ]); ?>

    <?php ActiveForm::end(); ?>

</div>