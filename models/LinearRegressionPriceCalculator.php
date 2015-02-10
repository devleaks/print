<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class LinearRegressionPriceCalculator extends PriceCalculator
{
	public $reg_a;
	public $reg_b;

	public function init() {
		if(!$this->item) return;
		$this->reg_a = Item::findOne(['reference' => $this->item->reference.'_A']);
		$this->reg_b = Item::findOne(['reference' => $this->item->reference.'_B']);
		if($this->reg_a && $this->reg_b) $this->inited = true;		
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


		$price = ($this->reg_a ? $this->reg_a->prix_de_vente : 0) * $x + ($this->reg_b ? $this->reg_b->prix_de_vente : 0);

		if($w < 60 && $h < 60)
			Yii::trace($w.'x'.$h.'='.$x.'=>'.$price, 'now');

		return round($price, 2);
	}
}