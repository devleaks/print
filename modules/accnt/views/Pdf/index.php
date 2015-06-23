<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PdfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'PDF Documents to Print');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdf-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
	        [
	            'label' => Yii::t('store', 'Document Type'),
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
				'attribute' => 'client_name',
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
	                    return file_exists($model->getFilepath()) ?
								Html::a('<i class="glyphicon glyphicon-eye-open"></i>',
									Url::to(['view', 'id' => $model->id]),
									[
			                        	'title' => Yii::t('store', 'View'),
										'target' => '_blank'
			                    	])
								:
								''
								;
	                },
	            ]
			],
        ],
    ]); ?>

</div>
