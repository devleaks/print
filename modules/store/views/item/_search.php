<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reference') ?>

    <?= $form->field($model, 'libelle_court') ?>

    <?= $form->field($model, 'libelle_long') ?>

    <?= $form->field($model, 'categorie') ?>

    <?php // echo $form->field($model, 'type_travaux_photos') ?>

    <?php // echo $form->field($model, 'type_numerique') ?>

    <?php // echo $form->field($model, 'fournisseur') ?>

    <?php // echo $form->field($model, 'reference_fournisseur') ?>

    <?php // echo $form->field($model, 'conditionnement') ?>

    <?php // echo $form->field($model, 'prix_d_achat_de_reference') ?>

    <?php // echo $form->field($model, 'client') ?>

    <?php // echo $form->field($model, 'quantite') ?>

    <?php // echo $form->field($model, 'prix_de_vente') ?>

    <?php // echo $form->field($model, 'date_initiale') ?>

    <?php // echo $form->field($model, 'date_finale') ?>

    <?php // echo $form->field($model, 'taux_de_tva') ?>

    <?php // echo $form->field($model, 'identification') ?>

    <?php // echo $form->field($model, 'suivi_de_stock') ?>

    <?php // echo $form->field($model, 'reassort_possible') ?>

    <?php // echo $form->field($model, 'seuil_de_commande') ?>

    <?php // echo $form->field($model, 'site_internet') ?>

    <?php // echo $form->field($model, 'creation') ?>

    <?php // echo $form->field($model, 'mise_a_jour') ?>

    <?php // echo $form->field($model, 'en_cours') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'commentaires') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
