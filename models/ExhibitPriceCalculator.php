<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class ExhibitPriceCalculator extends PriceCalculator
{

	public function init() {
		if(!$this->item) return;
		$this->items = [];
		$this->inited = true;		
	}

	
	public function price($w, $h, $min = false) {
		$len = $w + $h;
		$price = $this->item->prix_de_vente * $len / 50;
		// adjustment
		$base = ($this->item->reference == "Exhibite-X25Standard") ? $this->getPrice('MontageExhibiteBase2') : $this->getPrice('MontageExhibiteBase5');
		if($w < 30 || $h < 30) {
			$price += $base;
		} else if($len < 121) {
			$price += $base + ($h-30 + $w-30) * $this->getPrice('MontageExhibiteS');		
		} else if ($len < 130) {
			$price += $base + ($h-30) * $this->getPrice('MontageExhibiteMH') + ($w-20) * $this->getPrice('MontageExhibiteML');		
		} else {
			$price += $base + ($h-30 + $w-30) * $this->getPrice('MontageExhibiteL');		
		}
		return ceil($price);
	}
}