<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Parameter */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Parameter',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Parameters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
