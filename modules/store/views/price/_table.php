<?php
use app\models\Item;
use app\models\Parameter;
use app\models\DocumentLine;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
$stats = false;

$model = $priceCalculator->item;

$this->title = Yii::t('store', 'Price List').' '.$model->libelle_long;
if($model->reference == Item::TYPE_CHROMALUXE) {
	$w_max = Parameter::getIntegerValue('chroma_device', 'width');
	$h_max = Parameter::getIntegerValue('chroma_device', 'height');
	$max = max($w_max, $h_max);
	$min_w = 10;
	$max_w = $max;
	$stp_w = 10;
	$min_h = 10;
	$max_h = $max;
	$stp_h = 10;
	$w_max = $w_max;
	$h_max = $h_max;
	
} else {
	$wval = explode(',', Parameter::getTextValue('price_list', 'width'));
	$hval = explode(',', Parameter::getTextValue('price_list', 'height'));
	$min_w = $wval[0];
	$max_w = $wval[1];
	$stp_w = $wval[2];
	$min_h = $hval[0];
	$max_h = $hval[1];
	$stp_h = $hval[2];
}

?>
<div class="print-price">

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
			echo '<td class="text-center">'.$priceCalculator->price($w,$h).'</td>';//$h.'&times;'.$w
		}
		if ($stats) echo '<td class="text-center">'.DocumentLine::getDetailHeightCount('frame',$model->id,$h,$h+10).'</td>';//$h.'&times;'.$w
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
