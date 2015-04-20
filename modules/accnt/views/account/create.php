<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = Yii::t('store', 'Create Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_nosale', [
        'model' => $model,
    ]) ?>

</div>
