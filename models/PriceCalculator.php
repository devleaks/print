<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class PriceCalculator extends Model
{
	/** */
	const PERIMETER = 'P';
	/** */
	const SURFACE = 'S';
	
	protected $inited = false;
	public $reg_a;
	public $reg_b;
	
	/** Base item. If minimum price is requested, the price of this item IS the minimum price. */
	protected $items;

	public $item;

	/** Type of computation: Perimeter or Surface based. */
	public $type = self::PERIMETER;


	public function init() {
		if(!$this->item) return;
		$this->reg_a = Item::findOne(['reference' => $this->item->reference.'_A']);
		$this->reg_b = Item::findOne(['reference' => $this->item->reference.'_B']);
		if($this->reg_a && $this->reg_b) $this->inited = true;		
	}


	protected function getPrice($ref) {
		if(isset($this->items[$ref])) return $this->items[$ref];
		if( $item = Item::findOne(['reference' => $ref]) )
			return $this->items[$ref] = $item->prix_de_vente;
		return 0;
	}


	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *	@param boolean $min Whether there is a minimum price to be applied.
	 *
	 *	@return float Price of item for supplied width and height.
	 */
	public function price($w, $h, $min = false) {
		if(!$this->inited) return 0;

		$x = ($this->type == self::PERIMETER) ? 2 * ($w + $h) / 50 : $w * $h / 10000;

		$price = ($this->reg_a ? $this->reg_a->prix_de_vente : 0) * $x + ($this->reg_b ? $this->reg_b->prix_de_vente : 0);

		if($min && $price < $item->prix_de_vente)
			$price = $item->prix_de_vente;

		return round($price, 2);
	}
}