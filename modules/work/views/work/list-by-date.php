<?php

use app\models\Order;
use app\models\Work;
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
$this->title = Yii::t('store', 'Works '.Order::getDateWords($day));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
*/
?>


<?= $this->render('index', [
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
]) ?>