<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class ChromaLuxePriceCalculator extends PriceCalculator
{
	protected $w_max;
	protected $h_max;
	
	public $surfaces;
	public $prices;
	public $sizes = ['XS', 'S', 'M', 'L', 'XL'];


	public function init() {
		$this->item = Item::findOne(['reference' => Item::TYPE_CHROMALUXE]);
		$this->type = self::SURFACE;
		
		$this->w_max = Parameter::getIntegerValue('formule', 'SublimationMaxWidth');
		$this->h_max = Parameter::getIntegerValue('formule', 'SublimationMaxHeight');

		$this->prices = [];
		$this->surfaces = [];
		foreach($this->sizes as $size) {
			$this->prices[$size]   = Item::findOne(['reference' => 'Chroma'.$size]);
			$this->surfaces[$size] = Parameter::findOne(['domain' => 'formule', 'name' => 'ChromaLuxe'.$size]);
		}

		$this->inited = true;		
	}

	
	public function price($w, $h) {
		if(!$this->inited) return 0;

		// used to compute ratio to original device price
		$dev_w_max = Parameter::getIntegerValue('chroma_device', 'width');
		$dev_h_max = Parameter::getIntegerValue('chroma_device', 'height');

		$maxlen = min($this->w_max, $this->h_max);
		if($w > $maxlen && $h > $maxlen) return;

		$s = $w * $h;

		if( $item = $this->prices[$this->getSize($s)] ) {
			Yii::trace('category '.$this->getSize($s), 'ChromaLuxePriceCalculator::price');
			$price = ceil($item->prix_de_vente * $s / ($dev_w_max * $dev_h_max));
			return $price < $this->item->prix_min ? $this->item->prix_min : $price;
		}
		
		return 0; // error
	}
	

	public function getSize($s) {
		$i = 0;
		$max = count($this->sizes);
		while( ($i < $max) && ($s < $this->surfaces[$this->sizes[$max-$i-1]]->value_number) )
			$i++; 
		Yii::trace('surface='.$s.', index '.$i.' returing '.$this->sizes[($i > 0 ? $max-$i : $max - 1)], 'ChromaLuxePriceCalculator::getSize');
		return $this->sizes[($i > 0 ? $max-$i : $max - 1)];
	}
	
}