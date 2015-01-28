<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Master */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Master',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
