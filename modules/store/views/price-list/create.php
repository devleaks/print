<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PriceList */

$this->title = Yii::t('store', 'Create Price List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Composite Price Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
