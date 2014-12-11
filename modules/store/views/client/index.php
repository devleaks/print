<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Clients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create Client'), ['new'], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'reference_interne',
            // 'titre',
            'nom',
            'prenom',
            // 'autre_nom',
            // 'adresse',
            // 'code_postal',
            'localite',
            // 'pays',
            // 'langue',
            'numero_tva',
            // 'email:email',
            // 'site_web',
            // 'domicile',
            // 'bureau',
            // 'gsm',
            // 'fax_prive',
            // 'fax_bureau',
            // 'pc',
            // 'autre',
            // 'remise',
            // 'escompte',
            // 'delais_de_paiement',
            // 'mentions',
            // 'exemplaires',
            // 'limite_de_credit',
            // 'formule',
            // 'type',
            // 'execution',
            // 'support',
            // 'format',
            // 'mise_a_jour',
            // 'mailing',
            // 'outlook',
            // 'categorie_de_client',
            // 'comptabilite',
            // 'operation',
            // 'categorie_de_prix_de_vente',
            // 'reference_1',
            // 'date_limite_1',
            // 'reference_2',
            // 'date_limite_2',
            // 'reference_3',
            // 'date_limite_3',
            // 'commentaires',

	        [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view} {update} {delete} {doc} {accnt}',
	            'buttons' => [
	                'doc' => function ($url, $model) {
						$url = Url::to(['/order/document/client', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-shopping-cart"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
	                'accnt' => function ($url, $model) {
						$url = Url::to(['/accnt/account/client', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-book"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
				],
			],
        ],
    ]); ?>

</div>
