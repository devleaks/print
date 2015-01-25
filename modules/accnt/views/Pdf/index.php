<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PdfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Documents');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdf-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<div  class="alert alert-info" >
		Cette page est en cours de développement. Tous les éléments ne sont pas encore terminés. Pierre le 20-JAN-2015.
	</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
	        [
				'attribute' => 'document_type',
				'value' => function ($model, $key, $index, $widget) {
					return Yii::t('store', $model->getDocumentType());
				}
			],
	        [
				'attribute' => 'document_id',
				'value' => 'document.name'
			],
	        [
				'attribute' => 'client_id',
				'value' => function ($model, $key, $index, $widget) {
					if($cli = $model->getClient()->one() )
					return $cli->nom.($cli->email? ' ('.$cli->email.')' : '');
				}
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
	            'label' => Yii::t('store', 'Sent At'),
				'attribute' => 'sent_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				}
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
	            'buttons' => [
	                'view' => function ($url, $model) {
						$url = Url::to(['view', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'title' => Yii::t('store', 'View'), 'target' => '_blank'
	                    ]);
	                },
	            ]
			],
        ],
    ]); ?>

</div>
