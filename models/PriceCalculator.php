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
	
	/** Items that are loaded dynamically are cached in this array. */
	protected $items;

	/** whether init() has been called */
	protected $inited = false;
	

	/** Base item. If minimum price is requested, the price of this item IS the minimum price. */
	public $item;

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
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *
	 *	@return float Price of item for supplied width and height always rounded to larger integer.
	 */
	public function roundPrice($w, $h) {
		return ceil($this->price($w, $h));
	}


	/**
	 *	@param float $w Width, in centimeters
	 *	@param float $h Height, in centimeters
	 *
	 *	@return string Price of item for supplied width and height always rounded to larger integer and formatted as currency.
	 */
	public function formattedRoundPrice($w, $h) {
		return Yii::$app->formatter->asCurrency( ceil($this->price($w, $h)) );
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
	public function price($w, $h) {
		$this->init();
		return $this->item ? round($this->item->prix_de_vente, 2) : 0;
	}
	
}