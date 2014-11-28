<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ItemOption */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Item Option',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Item Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-option-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
