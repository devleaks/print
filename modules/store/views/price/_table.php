<?php
use app\models\Item;
use app\models\Parameter;
use app\models\DocumentLine;
use app\models\PriceCalculator;
use app\models\ChromaLuxePriceCalculator;
use app\models\ExhibitPriceCalculator;
use app\models\MontagePriceCalculator;
use app\models\RenfortPriceCalculator;
use yii\helpers\Html;
use kartik\widgets\TouchSpin;

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

$can_adjust = is_a($priceCalculator, PriceCalculator::className())
					&& !is_a($priceCalculator, ChromaLuxePriceCalculator::className())
					&& !is_a($priceCalculator, ExhibitPriceCalculator::className())
					&& !is_a($priceCalculator, MontagePriceCalculator::className())
					&& !is_a($priceCalculator, RenfortPriceCalculator::className());
$chromaluxe = is_a($priceCalculator, ChromaLuxePriceCalculator::className());
?>
<div class="print-price">

    <h1><?= Html::encode($this->title) . (($can_adjust || $chromaluxe) && !$print ? ' <a href="#" id="adjust-price-button" class="btn btn-sm btn-primary">'.Yii::t('store', 'Adjust Price').'</a>' : '') ?></h1>

	<div id="adjusts-price">

<?php if($can_adjust && !$print): ?>
	<div class="row">
	
		<div class="col-lg-2">
			<?= TouchSpin::widget([
						'name' => 'reg_a',
						'pluginOptions' => [
							'initval' => $priceCalculator->reg_a->prix_de_vente,
							'verticalbuttons' => true,
							'min' => 0,
							'max' => 200,
							'step' => 0.1,
							'decimals' => 1,
						],
						'options' => ['class' => 'adjust-reg'],
			]) ?>
		</div>

		<div class="col-lg-2">
			<?= TouchSpin::widget([
						'name' => 'reg_b',
						'pluginOptions' => [
							'initval' => $priceCalculator->reg_b ? $priceCalculator->reg_b->prix_de_vente : 0,
							'verticalbuttons' => true,
							'min' => 0,
							'max' => 200,
							'step' => 0.1,
							'decimals' => 1,
						],
						'options' => ['class' => 'adjust-reg'],
			]) ?>
		</div>

	</div>
<?php endif; ?>

<?php if($chromaluxe && !$print): ?>
	<div class="row">
		
		<?php foreach($priceCalculator->sizes as $size): ?>
		<div class="col-lg-2">
			<?= TouchSpin::widget([
						'name' => 'price'.$size,
						'pluginOptions' => [
							'initval' => $priceCalculator->prices[$size]->prix_de_vente,
							'verticalbuttons' => true,
							'min' => 0,
							'max' => 1000,
							'step' => 0.1,
							'decimals' => 1,
						],
						'options' => ['class' => 'adjust-chroma-price'],
			]) ?>
			
		</div>
		<?php endforeach; ?>

	</div>

	<br/>
	<div class="row">
	
		<?php foreach($priceCalculator->sizes as $size): ?>
		<div class="col-lg-2 chroma<?=$size?>">
			<?= TouchSpin::widget([
						'name' => 'size'.$size,
						'pluginOptions' => [
							'initval' => $priceCalculator->surfaces[$size]->value_number,
							'verticalbuttons' => true,
							'min' => 0,
							'max' => ($w_max * $h_max),
							'step' => 100,
						],
						'options' => ['class' => 'adjust-chroma-surface'],
			]) ?>
		</div>
		<?php endforeach; ?>

	</div>
<?php endif; ?>

	<br/>
	<br/>

	</div><!--adjusts-price-->

	<div class="row">

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
			if($chromaluxe)
				echo '<td class="text-center adjust chroma'.$priceCalculator->getSize($w*$h).'" data-xval="'.($priceCalculator->type == $priceCalculator::SURFACE ? ($w*$h) : ($w+$h)/50).'">'.$priceCalculator->price($w,$h).'</td>';//$h.'&times;'.$w
			else
				echo '<td class="text-center adjust" data-xval="'.($priceCalculator->type == $priceCalculator::SURFACE ? ($w*$h/10000) : ($w+$h)/50).'">'.$priceCalculator->price($w,$h).'</td>';//$h.'&times;'.$w
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

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_ADJUSTPRICE'); ?>
$("#adjusts-price").toggle(false);

$("#adjust-price-button").click(function(){
	$("#adjusts-price").toggle();
});

$(".adjust-reg").change(function() {
	reg_a = parseFloat($("input[name='reg_a']").val());
	reg_b = parseFloat($("input[name='reg_b']").val());
	$(".adjust").each(function() {
		x = parseFloat($(this).data('xval'));
		p = reg_a * x + reg_b;
		$(this).html(Math.round(100*p)/100);
	});
});

<?php if($chromaluxe): ?>
surfaces = [<?php $f = true; foreach($priceCalculator->sizes as $size) {
							if(!$f) echo ','; $f = false;
							echo $priceCalculator->surfaces[$size]->value_number; } ?>];
prices = [<?php $f = true; foreach($priceCalculator->sizes as $size) {
							if(!$f) echo ','; $f = false;
							echo $priceCalculator->prices[$size]->prix_de_vente; } ?>];
sizes = ['XS', 'S', 'M', 'L', 'XL'];
chroma =  {
	w_max: <?= $w_max ?>,
	h_max: <?= $h_max ?>,
	s_max: <?= $h_max * $w_max ?>,
	min_price: <?= Item::findOne(['reference'=>'ChromaMin'])->prix_de_vente ?>
}

function getSize(s) {
	i = 0;
	while( (i < sizes.length) && (s > surfaces[i]) )
		i++;
//	console.log(s+'<'+surfaces[i]+' i='+i);
	return i >= sizes.length ? sizes.length - 1 : i;
}

function chroma_price(s) {
	size = getSize(s);
	uprice = prices[size];
	price = Math.ceil(uprice * s / (chroma.w_max * chroma.h_max));
//	if (s = 3600) console.log(s+'=>'+size+"->"+uprice+"="+price);
	return price < chroma.min_price ? chroma.min_price : price;
}

$(".adjust-chroma-price").change(function() {
	console.log('yup');
	for(i=0;i<sizes.length;i++) {
		prices[i] = parseFloat($("input[name='price"+sizes[i]+"']").val());
		console.log(prices[i]+','+surfaces[i]);
	}
	$(".adjust").each(function() {
		x = parseFloat($(this).data('xval'));
		p = ((x > 0) && (x <= chroma.s_max))? chroma_price(x) : 0;
		$(this).html(x <= chroma.s_max ? Math.round(100*p)/100 : '');
	});
});

$(".adjust-chroma-surface").change(function() {
	for(i=0;i<sizes.length;i++) {
		surfaces[i] = parseFloat($("input[name='size"+sizes[i]+"']").val());
//		console.log(prices[i]+',S'+surfaces[i]);
	}
	$(".adjust").each(function() {
		for(i=0;i<sizes.length;i++) {
			$(this).removeClass('chroma'+sizes[i]);
		}
		x = parseFloat($(this).data('xval'));
		p = x > 0 ? getSize(x) : 0;
		$(this).addClass('chroma'+sizes[p]);
	});
});
<?php endif; ?>

<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_ADJUSTPRICE'], yii\web\View::POS_END);