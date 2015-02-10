<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class RenfortPriceCalculator extends PriceCalculator
{
	public $support;
	public $inside = 0;
	
	public function init() {
		if(!$this->item) return;
		$this->inited = true;
	}


	function setSupport($s) {
		Yii::trace($s ? $s->reference : 'null', 'RenfortPriceCalculator::setSupport');
		$this->support = $s;
		$this->inside = $this->support? ($this->support->reference == Item::TYPE_CHROMALUXE ? 40 : 20) : 20;
	}

	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *	@param boolean $min Whether there is a minimum price to be applied.
	 *
	 *	@return float Price of item for supplied width and height.
	 */
	public function price($w, $h) {
		if(!$this->inited) return 0;

		$x = ($w + $h - $this->inside) / 50;
		$price = $this->getPrice('Renfort') * $x;
		Yii::trace('w='.$w.', h='.$h.', in='.$this->inside.', p='.(100*$x).' €='.$price, 'RenfortPriceCalculator::price');

		$minPrice = $this->getPrice('Renfort_Min');
		if($price < $minPrice) $price = $minPrice;

		return round($price, 2);
	}

}