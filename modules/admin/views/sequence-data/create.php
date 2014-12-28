<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SequenceData */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Sequence Data',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Sequence Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
