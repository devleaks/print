<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Extraction Line',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Extraction Lines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extraction-line-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
