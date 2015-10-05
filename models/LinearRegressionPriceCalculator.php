<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class LinearRegressionPriceCalculator extends PriceCalculator
{
	public function init() {
		if(!$this->item) return;
		$this->inited = true;		
	}


	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *
	 *	@return float Price of item for supplied width and height.
	 */
	public function price($w, $h) {
		if(!$this->inited) return 0;

		$x = ($this->type == self::PERIMETER) ? ($w + $h) / 50 : $w * $h / 10000;


		$price = ($this->item->prix_a ? $this->item->prix_a : 0) * $x + ($this->item->prix_b ? $this->item->prix_b : 0);

		if($w < 60 && $h < 60)
			Yii::trace($w.'x'.$h.'='.$x.'=>'.$price, 'now');

		return round($price, 2);
	}
}