<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($document_type))
	$document_type = 'doc';

$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;

$this->title = Yii::t('store', Document::getTypeLabel($document_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [in_array($role, ['manager', 'admin']) ? '/store' : '/order']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create '.ucfirst(strtolower($document_type))), ['create-'.strtolower($document_type)], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
	        [
				'attribute' => 'document_type',
	            'label' => Yii::t('store', 'Type'),
				'filter' => Document::getDocumentTypes(),
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->document_type);
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
				'attribute' => 'price_tvac',
				'format' => 'currency',
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
//				'options' => ['class' => 'IntroJS1'],
//	        ],
            [	// freely let update or delete if accessed throught this screen.
				'class' => 'kartik\grid\ActionColumn',
				'controller' => 'document',
//			 	'template' => '{update} {delete}'
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