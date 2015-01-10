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


function price_chromaluxe($w, $h, $p, $w_max, $h_max) {
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

?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

<table class='table table-striped table-bordered table-condensed'>
	<thead>
	<tr>
<?php
	echo '<th class="text-center">'.Yii::t('store', 'Dimensions').'</th>';
	for($w = 20; $w <= 170; $w = $w + 10) {
		echo '<th class="text-center">'.$w.'</th>';
	}
?>
	</tr>
	</thead>
	<tbody>
<?php
	for($h = 20; $h <= 110; $h = $h + 10) {
		echo '<tr>';
		echo '<th class="text-center">'.$h.'</th>';
		for($w = 20; $w <= 170; $w = $w + 10) {
			echo '<td class="text-center">'.price_chromaluxe($w,$h,$parameters,$w_max, $h_max).'</td>';//$h.'&times;'.$w
		}
		echo '</tr>';
	}

?>
	</tbody>
<table>

</div>
