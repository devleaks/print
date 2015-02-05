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
	
	/** whether init() has been called */
	protected $inited = false;
	
	/** Base item. If minimum price is requested, the price of this item IS the minimum price. */
	public $item;

	/** Items that are loaded dynamically are cached in this array. */
	protected $items;

	/** Type of computation: Perimeter or Surface based. Default to PERIMETER */
	public $type = self::PERIMETER;


	/**
	 * Loads an item (by reference) and caches it. Return the item's price if found.
	 *
	 *	@param string $ref Item's reference.
	 *
	 *	@return float The item's price.
	 */
	protected function getPrice($ref) {
		if(isset($this->items[$ref])) return $this->items[$ref];
		if( $item = Item::findOne(['reference' => $ref]) )
			return $this->items[$ref] = $item->prix_de_vente;
		return 0;
	}


	/**
	 * Rounds a price to nearest 0.5.
	 */
	public function roundPrice($w, $h, $min = false) {
		return round(2 * $this->price($w, $h, $min), 0) / 2;
	}


	/**
	 * Initialisation routine for price calculator. Called exactly once.
	 *
	 *	@return nothing.
	 */
	public function init() {
		if(!$this->inited) {
			if(!$this->item) return;
			$this->items = [];
			$this->inited = true;
		}
	}


	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *	@param boolean $min Whether there is a minimum price to be applied.
	 *
	 *	@return float Price of item for supplied width and height.
	 */
	public function price($w, $h, $min = false) {
		$this->init();
		return round($this->item->prix_de_vente, 2);
	}
	
}