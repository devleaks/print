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

<?= $this->render('_table-chromaluxe', [
		            'model' => $model,
					'parameters' => $parameters,
					'min_w' => $min_w,
					'max_w' => $max_w,
					'stp_w' => $stp_w,
					'min_h' => $min_h,
					'max_h' => $max_h,
					'stp_h' => $stp_h,
					'w_max' => $w_max,
					'h_max' => $h_max,
					'stats' => $stats
]) ?>

<?= Html::a(Yii::t('store', 'Print'), Url::to(['print', 'id' => $model->id]), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
