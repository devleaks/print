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

<?= $this->render('_table-support', [
		            'model' => $model,
					'reg_a' => isset($reg_a)?$reg_a : null,
					'reg_b' => isset($reg_b)?$reg_b : null,
					'min_w' => $min_w,
					'max_w' => $max_w,
					'stp_w' => $stp_w,
					'min_h' => $min_h,
					'max_h' => $max_h,
					'stp_h' => $stp_h,
					'stats' => $stats
	]) ?>

<?= Html::a(Yii::t('store', 'Print'), Url::to(['print', 'id' => $model->id]), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
