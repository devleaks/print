<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($order_type))
	$order_type = 'doc';

$this->title = Yii::t('store', Document::getTypeLabel($order_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create '.Document::getTypeLabel($order_type)), ['create-'.strtolower($order_type)], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
	        [
				'attribute' => 'order_type',
	            'label' => Yii::t('store', 'Type'),
				'filter' => Document::getDocumentTypes(),
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->order_type);
				}
			],
	        [
				'attribute' => 'name',
	            'label' => Yii::t('store', 'Référence'),
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
				'attribute' => 'price_htva',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			],
			[
				'attribute' => 'due_date',
				'format' => 'date',
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
	        [
	            'label' => Yii::t('store', 'Actions'),
	            'value' => function ($model, $key, $index, $widget) {
							return $model->getActions('btn btn-xs', false, '{icon}');
	            		},
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
				'noWrap' => true,
				'options' => ['class' => 'IntroJS1'],
	        ],
            [	// freely let update or delete if accessed throught this screen.
				'class' => 'yii\grid\ActionColumn',
				'controller' => 'order',
			 	'template' => '{update} {delete}'
			],

        ],
    ]); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_INIT'); ?>
function addIntroJs(sid,intro) { $(sid).attr('data-intro', intro); }
addIntroJs('a[data-sort="name"]', "Tri pour cette colonne");
addIntroJs('table thead tr', "Champs de tri");
addIntroJs('input[name="DocumentSearch[name]"]', "Champ de recherche pour cette colonne");
addIntroJs('.filters', "Champs de sélection");
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);