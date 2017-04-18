<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use app\models\User;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('store', 'Customer {0}', [ucfirst(strtolower($client->nom))]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [User::hasRole(['manager', 'admin']) ? '/store' : '/order']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= Yii::$app->controller->renderPartial('../../../stats/views/order/_client_per_year', ['model'=> ['client_id' => $client->id] ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
//	        [
//				'attribute' => 'client_name',
//	            'label' => Yii::t('store', 'Client'),
//	            'value' => function ($model, $key, $index, $widget) {
//							return $model->client->nom;
//				}
//			],
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
	            'label' => Yii::t('store', 'Amount TVAC'),
				'attribute' => 'price_tvac',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
					'pageSummary' => true
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
				'class'	=> 'app\widgets\DocumentActionColumn',
				'noWrap' => true,
				'hAlign' => GridView::ALIGN_CENTER,
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