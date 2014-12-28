<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = Yii::t('store', 'Price List').' '.$model->libelle_long;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

function price_linreg($w, $h, $a, $b, $surf) {
	$qty = $surf ? ($w * $h / 10000) : (($w + $h) / 50); // 2 * (w + h) / 100 in meters, 100cmX100cm=10000cm2 in a m2
	return ceil($a * $qty + $b);
	//return number_format($a * $qty + $b, 2, ',', '');
}

?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

<table class='table table-striped table-bordered table-condensed'>
	<thead>
	<tr>
<?php
	echo '<th class="text-center">'.Yii::t('store', 'Dimensions').'</th>';
	for($w = 30; $w < 200; $w = $w + 10) {
		echo '<th class="text-center">'.$w.'</th>';
	}
?>
	</tr>
	</thead>
	<tbody>
<?php
	for($h = 40; $h < 200; $h = $h + 10) {
		echo '<tr>';
		echo '<th class="text-center">'.$h.'</th>';
		for($w = 30; $w < 200; $w = $w + 10) {
			echo '<td class="text-center">'.price_linreg($w,$h,$reg_a,$reg_b,$use_surface).'</td>';//$h.'&times;'.$w
		}
		echo '</tr>';
	}

?>
	</tbody>
<table>

</div>
