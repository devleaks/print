<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class MontagePriceCalculator extends PriceCalculator
{
	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *	@param boolean $min Whether there is a minimum price to be applied.
	 *
	 *	@return float Price of item for supplied width and height.
	 */
	public function price($w, $h, $min = false) {
		$limit = Parameter::getIntegerValue('formule','LargeFrame');

		$price = ($w + $h) > $limit ? $this->getPrice('Montage170L') : $this->getPrice('Montage170S');

		return round($price, 2);
	}

}