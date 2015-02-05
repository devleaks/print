<?php

use app\models\ItemCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Price Lists');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'reference',
            'libelle_court',
            'libelle_long',
	        [
	            'attribute' => 'categorie',
	            'filter' => [
					ItemCategory::CHROMALUXE => Yii::t('store', ItemCategory::CHROMALUXE),
					ItemCategory::RENFORT => Yii::t('store', ItemCategory::RENFORT),
					ItemCategory::SUPPORT => Yii::t('store', ItemCategory::SUPPORT),
					ItemCategory::FRAME => Yii::t('store', ItemCategory::FRAME),
					ItemCategory::UV => Yii::t('store', ItemCategory::UV),
					ItemCategory::PROTECTION => Yii::t('store', ItemCategory::PROTECTION),
					ItemCategory::MONTAGE => Yii::t('store', ItemCategory::MONTAGE),
	        	],
			],
            'fournisseur',
            'prix_de_vente',
            'taux_de_tva',
            'status',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {print}',
				'buttons' => [
	                'view' => function ($url, $model) {
						$url = Url::to(['view', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
	                'print' => function ($url, $model) {
						$url = Url::to(['print', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-print"></i>', $url, [
	                        'title' => Yii::t('store', 'Print'), 'target' => '_blank',
	                    ]);
	                },
				]
			],
        ],
    ]); ?>

</div>
