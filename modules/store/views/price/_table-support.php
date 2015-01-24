<?php
use app\models\Parameter;
use app\models\DocumentLine;
use app\models\Item;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = Yii::t('store', 'Price List').' '.$model->libelle_long;

function price_linreg($w, $h, $a, $b, $surf) {
	$qty = $surf ? ($w * $h / 10000) : (($w + $h) / 50); // 2 * (w + h) / 100 in meters, 100cmX100cm=10000cm2 in a m2
	return round($a * $qty + $b, 2);
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
	for($w = $min_w; $w <= $max_w; $w = $w + $stp_w) {
		echo '<th class="text-center">'.$w.'</th>';
	}
	if ($stats) echo '<th class="text-center">'.Yii::t('store', 'Quantity').'</th>';
?>
	</tr>
	</thead>
	<tbody>
<?php
	for($h = $min_h; $h <= $max_h; $h = $h + $stp_h) {
		echo '<tr>';
		echo '<th class="text-center">'.$h.'</th>';
		for($w = $min_w; $w <= $max_w; $w = $w + $stp_w) {
			echo '<td class="text-center">'.price_linreg($w,$h,$reg_a,$reg_b,true).'</td>';
		}
		if ($stats) echo '<td class="text-center">'.DocumentLine::getDetailHeightCount('support',$model->id,$h,$h+10).'</td>';//$h.'&times;'.$w
		echo '</tr>';
	}
	if ($stats) { // quantity line
		echo '<tr>';
		echo '<th class="text-center">'.Yii::t('store', 'Quantity').'</th>';
		$total = 0;
		for($w = $min_w; $w <= $max_w; $w = $w + $stp_w) {
			$cnt = DocumentLine::getDetailWidthCount('frame',$model->id,$w,$w+10);
			$total += $cnt;
			echo '<td class="text-center">'.$cnt.'</td>';//$h.'&times;'.$w
		}
		echo '<th class="text-center">'.$total.'</th>';//$h.'&times;'.$w
		echo '</tr>';
	}

?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="<?= ceil($max_w/$stp_w) + 1 ?>" style="text-align: right; font-size: 9px;"><?= date('d-m-Y') ?></td>
		</tr>
	</tfoot>
</table>

</div>
