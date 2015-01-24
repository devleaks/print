<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = Yii::t('store', 'Price List').' '.$model->libelle_long;

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $content ?>

<?= Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('store', 'Print'), Url::to(['print', 'id' => $model->id]), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
