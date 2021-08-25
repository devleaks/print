<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class RenfortPriceCalculator extends PriceCalculator {

	public $support;
	public $frame;
	public $inside = 0;
	
	public function init() {
		if(!$this->item)
			return;
		$this->inited = true;
	}

	function setSupport($s) {
		$this->support = $s;
		$this->inside = 0; // $this->support? ($this->support->reference == Item::TYPE_CHROMALUXE ? 40 : 20) : 20;
	}

	function setFrame($f) {
		$this->frame = $f;
	}

	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *
	 *	@return float Price of item for supplied width and height always rounded to 2 decimals.
	 */
	public function price($w, $h) {
		if(!$this->inited) return 0;

		if($this->frame) {
			$maxWidth = Parameter::getIntegerValue('formule', 'RenfortMaxWidth');
			$maxHeight = Parameter::getIntegerValue('formule', 'RenfortMaxHeight');
			if($w > $maxWidth || $h > $maxHeight) // force renfort, but it is free
				return 0;
		}
		$x = ($w + $h - $this->inside) / 50;

		$price = $this->item->prix_de_vente > 0 ? $this->item->prix_de_vente * $x :
					$this->item->prix_a * $x + $this->item->prix_b;
//		Yii::trace('w='.$w.', h='.$h.', in='.$this->inside.', p='.(100*$x).' €='.$price, 'RenfortPriceCalculator::price');

		$minReference = $this->item->reference . self::MIN_PRICE;
		$minPrice = $this->getPrice($minReference);
		// Yii::trace('min price for '.$minReference.'='.$minPrice);
		if($price < $minPrice) $price = $minPrice;

		return round($price, 2);
	}

}