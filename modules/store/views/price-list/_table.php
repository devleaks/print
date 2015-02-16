<?php
use app\models\Item;
use app\models\ItemCategory;
use app\models\PriceListItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
$stats = false;
$sizes = explode(',', $model->sizes);
$items = [];
foreach($model->getPriceListItems()->orderBy('position')->each() as $pli) {
	$i = new stdClass();
	$i->name = $pli->item->libelle_court;
	$i->pos = $pli->position;
	$i->pc = $pli->item->getPriceCalculator();
	if($pli->item->yii_category == ItemCategory::RENFORT) { // try to find item is same position
		if( $support = PriceListItem::find()
				->andWhere(['price_list_id' => $pli->price_list_id, 'position' => $pli->position]) // same pos
				->andWhere(['not', ['item_id' => $pli->item_id]])->one() ) // but not itself
		$i->pc->setSupport($support->item);
	}
	$items[] = $i;
}
?>
<div class="print-price">

    <h1><?= Html::encode($model->name) ?></h1>

	<div class="row">

<table class='table table-striped table-bordered table-condensed'>
	<thead>
	<tr>
<?php
	echo '<th class="text-center">'.Yii::t('store', 'Dimensions').'</th>';
	$pos = null;
	$str = '';
	foreach($items as $item) {
		if(!$pos) { // first
			$str = $item->name;
			$pos = $item->pos;
		} else if ($pos == $item->pos) {
			$str .= (' + '.$item->name);
		} else {
			echo '<th class="text-center">'.$str.'</th>';
			$str = $item->name;
			$pos = $item->pos;
		}
	}
	echo '<th class="text-center">'.$str.'</th>';
	if ($stats) echo '<th class="text-center">'.Yii::t('store', 'Quantity').'</th>';
?>
	</tr>
	</thead>
	<tbody>
<?php
	foreach($sizes as $dimstr) {
		$dim = explode('x', $dimstr);
		echo '<tr>';
		echo '<th class="text-center">'.$dim[0].'&times;'.$dim[1].'</th>';
		$pos = null;
		$prc = '';
		foreach($items as $item) {
			if(!$pos) { // first
				$prc = ($item->pc ? $item->pc->roundPrice($dim[0],$dim[1]) : 0);
				$pos = $item->pos;
			} else if ($pos == $item->pos) {
				$prc += ($item->pc ? $item->pc->roundPrice($dim[0],$dim[1]) : 0);
			} else {
				echo '<td class="text-center">'.Yii::$app->formatter->asCurrency($prc).'</td>';//$h.'&times;'.$w
				$prc = ($item->pc ? $item->pc->roundPrice($dim[0],$dim[1]) : 0);
				$pos = $item->pos;
			}
		}
		echo '<td class="text-center">'.Yii::$app->formatter->asDecimal($prc, 2).'</td>';//$h.'&times;'.$w
		if ($stats) echo '<td class="text-center">'.'stats'.'</td>';//$h.'&times;'.$w
		echo '</tr>';
	}
	if ($stats) { // quantity line
		echo '<tr>';
		echo '<th class="text-center">'.Yii::t('store', 'Quantity').'</th>';
		$total = 0;
		foreach($items as $item) {
			$cnt = 1;
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
			<td colspan="<?= $model->getPriceListItems()->select('position')->distinct()->count() + 1 ?>" style="text-align: right; font-size: 9px;">
				<?= Yii::t('print', 'All price VAT excluded.') .Yii::t('print', 'Printed on').' ' .date('d-m-Y') ?>
			</td>
		</tr>
	</tfoot>
</table>

	</div>

</div>