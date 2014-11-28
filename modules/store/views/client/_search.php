<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reference_interne') ?>

    <?= $form->field($model, 'titre') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'prenom') ?>

    <?php // echo $form->field($model, 'autre_nom') ?>

    <?php // echo $form->field($model, 'adresse') ?>

    <?php // echo $form->field($model, 'code_postal') ?>

    <?php // echo $form->field($model, 'localite') ?>

    <?php // echo $form->field($model, 'pays') ?>

    <?php // echo $form->field($model, 'langue') ?>

    <?php // echo $form->field($model, 'numero_tva') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'site_web') ?>

    <?php // echo $form->field($model, 'domicile') ?>

    <?php // echo $form->field($model, 'bureau') ?>

    <?php // echo $form->field($model, 'gsm') ?>

    <?php // echo $form->field($model, 'fax_prive') ?>

    <?php // echo $form->field($model, 'fax_bureau') ?>

    <?php // echo $form->field($model, 'pc') ?>

    <?php // echo $form->field($model, 'autre') ?>

    <?php // echo $form->field($model, 'remise') ?>

    <?php // echo $form->field($model, 'escompte') ?>

    <?php // echo $form->field($model, 'delais_de_paiement') ?>

    <?php // echo $form->field($model, 'mentions') ?>

    <?php // echo $form->field($model, 'exemplaires') ?>

    <?php // echo $form->field($model, 'limite_de_credit') ?>

    <?php // echo $form->field($model, 'formule') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'execution') ?>

    <?php // echo $form->field($model, 'support') ?>

    <?php // echo $form->field($model, 'format') ?>

    <?php // echo $form->field($model, 'mise_a_jour') ?>

    <?php // echo $form->field($model, 'mailing') ?>

    <?php // echo $form->field($model, 'outlook') ?>

    <?php // echo $form->field($model, 'categorie_de_client') ?>

    <?php // echo $form->field($model, 'comptabilite') ?>

    <?php // echo $form->field($model, 'operation') ?>

    <?php // echo $form->field($model, 'categorie_de_prix_de_vente') ?>

    <?php // echo $form->field($model, 'reference_1') ?>

    <?php // echo $form->field($model, 'date_limite_1') ?>

    <?php // echo $form->field($model, 'reference_2') ?>

    <?php // echo $form->field($model, 'date_limite_2') ?>

    <?php // echo $form->field($model, 'reference_3') ?>

    <?php // echo $form->field($model, 'date_limite_3') ?>

    <?php // echo $form->field($model, 'commentaires') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('store', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
