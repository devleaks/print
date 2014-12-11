<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Client */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id, 'reference_interne' => $model->reference_interne], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id, 'reference_interne' => $model->reference_interne], [
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
            'reference_interne',
            'titre',
            'nom',
            'prenom',
            'autre_nom',
            'adresse',
            'code_postal',
            'localite',
            'pays',
            'langue',
            'numero_tva',
            'email:email',
            'site_web',
            'domicile',
            'bureau',
            'gsm',
            'fax_prive',
            'fax_bureau',
            'pc',
            'autre',
            'comptabilite',
            'commentaires',
            'remise',
            'escompte',
            'delais_de_paiement',
            'mentions',
            'exemplaires',
            'limite_de_credit',
            'formule',
            'type',
            'execution',
            'support',
            'format',
            'mise_a_jour',
            'mailing',
            'outlook',
            'categorie_de_client',
            'operation',
            'categorie_de_prix_de_vente',
            'reference_1',
            'date_limite_1',
            'reference_2',
            'date_limite_2',
            'reference_3',
            'date_limite_3',
        ],
    ]) ?>

</div>
