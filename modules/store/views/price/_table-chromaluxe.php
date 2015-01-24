<?php
use app\models\Item;
use app\models\DocumentLine;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = Yii::t('store', 'Price List').' '.$model->libelle_long;

function price_chromaluxe($w, $h, $p, $w_max, $h_max) {
	$maxlen = min($w_max, $h_max);
	if($w > $maxlen && $h > $maxlen) return;

	$s = $w * $h;

	$i = 0;
	while($i < count($p) && $s < $p[$i]['value_number'])
		$i++;

	if($i > 0) $i--;
	
	if( $item = Item::findOne(['reference' => str_replace('ChromaLuxe', 'Chroma', $p[$i]['name'])]) ) {
		//Yii::trace($w.'x'.$h.'='.$s.' < '.$p[$i]['value_number'].' i='.$i.', price='.$item->prix_de_vente);
		return ceil($item->prix_de_vente * $s / ($w_max * $h_max));
	}
		
	return 0; // error
}

$item_id = Item::findOne(['reference' => Item::TYPE_CHROMALUXE])->id;

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
			echo '<td class="text-center">'.price_chromaluxe($w,$h,$parameters,$w_max, $h_max).'</td>';//$h.'&times;'.$w
		}
		if ($stats) echo '<td class="text-center">'.DocumentLine::getHeightCount($item_id, $h,$h+10).'</td>';//$h.'&times;'.$w
		echo '</tr>';
	}
	if ($stats) { // quantity line
		echo '<tr>';
		echo '<th class="text-center">'.Yii::t('store', 'Quantity').'</th>';
		$total = 0;
		for($w = $min_w; $w <= $max_w; $w = $w + $stp_w) {
			$cnt = DocumentLine::getWidthCount($item_id, $w,$w+10);
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
