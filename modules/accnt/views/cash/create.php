<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cash */

$this->title = Yii::t('store', 'Create Cash Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Cash'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
