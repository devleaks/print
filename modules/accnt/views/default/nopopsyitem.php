<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create Item'), ['create'], ['class' => 'btn btn-success']) ?>
	</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'reference',
            'libelle_court',
            'libelle_long',
            'categorie',
            // 'type_travaux_photos',
            // 'type_numerique',
             'fournisseur',
            // 'reference_fournisseur',
            // 'conditionnement',
            // 'prix_d_achat_de_reference',
            // 'client',
            // 'quantite',
            'prix_de_vente',
            //'date_initiale',
            //'date_finale',
            'taux_de_tva',
            // 'identification',
            // 'suivi_de_stock',
            // 'reassort_possible',
            // 'seuil_de_commande',
            // 'site_internet',
            // 'creation',
            // 'mise_a_jour',
            // 'en_cours',
            // 'stock',
            // 'commentaires',
            'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'kartik\grid\ActionColumn','noWrap'=>true],
        ],
    ]); ?>

</div>
