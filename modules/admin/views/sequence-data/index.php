<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SequenceDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Sequence Numbers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'sequence_name',
            'sequence_cur_value',
            'sequence_year',
            'sequence_min_value',
            'sequence_increment',
            //'sequence_max_value',
            //'sequence_cycle',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
