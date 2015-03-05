<?php

use app\models\Parameter;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Parameters');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create Parameter'), ['create'], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

	        [
	            'attribute' => 'domain',
	            'filter' => Parameter::getDomains(),
	        ],
            'name',
            'lang',
            'value_text',
            'value_number',
            'value_int',
            'value_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
