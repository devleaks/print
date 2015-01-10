<?php
use app\models\Item;
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

function getPrice($ref) {
	if( $item = Item::findOne(['reference' => $ref]) )
		return $item->prix_de_vente;
	return 0;
}

function price_exhibit($w, $h, $frame) {
	$len = $w + $h;
	$price = $frame->prix_de_vente * $len / 50;
	// adjustment
	$base = ($frame->reference == "Exhibite-X25Standard") ? getPrice('MontageExhibiteBase2') : getPrice('MontageExhibiteBase5');
	if($w < 30 || $h < 30) {
		$price += $base;
	} else if($len < 121) {
		$price += $base + ($h-30 + $w-30) * getPrice('MontageExhibiteS');		
	} else if ($len < 130) {
		$price += $base + ($h-30) * getPrice('MontageExhibiteMH') + ($w-20) * getPrice('MontageExhibiteML');		
	} else {
		$price += $base + ($h-30 + $w-30) * getPrice('MontageExhibiteL');		
	}
	return ceil($price);
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
?>
	</tr>
	</thead>
	<tbody>
<?php
	for($h = $min_h; $h <= $max_h; $h = $h + $stp_h) {
		echo '<tr>';
		echo '<th class="text-center">'.$h.'</th>';
		for($w = $min_w; $w <= $max_w; $w = $w + $stp_w) {
			echo '<td class="text-center">'.( $model->fournisseur == 'Exhibit' ?
												price_exhibit($w, $h, $model) :
												price_linreg($w,$h,$reg_a,$reg_b,false) ).'</td>';//$h.'&times;'.$w
		}
		echo '</tr>';
	}

?>
	</tbody>
<table>

</div>
