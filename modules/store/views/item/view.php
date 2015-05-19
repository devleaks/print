<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = $model->libelle_court;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'reference',
            'libelle_court',
            'libelle_long',
            'prix_de_vente',
            'taux_de_tva',
            'commentaires',
            'categorie',
            'type_travaux_photos',
            'type_numerique',
            'fournisseur',
            'reference_fournisseur',
            'yii_category',
            'status',
            'created_at',
            'updated_at',
/*          'conditionnement',
            'prix_d_achat_de_reference',
            'client',
            'quantite',
            'prix_de_vente',
            'date_initiale',
            'date_finale',
            'identification',
            'suivi_de_stock',
            'reassort_possible',
            'seuil_de_commande',
            'site_internet',
            'creation',
            'mise_a_jour',
            'en_cours',
            'stock',
*/        ],
    ]) ?>

	<?= $this->render('../item-task/list', ['model'=>$model]) ?>

	<?= ' '/*$this->render('../item-option/list', ['model'=>$model])*/ ?>

</div>
