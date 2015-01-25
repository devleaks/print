<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class NielsenPriceCalculator extends PriceCalculator
{

	public function init() {
		if(!$this->item) return;
		$this->reg_a = $this->item;
		$this->reg_b = null;
		if($this->reg_a) $this->inited = true;		
	}

}