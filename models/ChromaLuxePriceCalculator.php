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
	protected $min_price;
	
	public $surfaces;
	public $prices;
	public $sizes = ['XS', 'S', 'M', 'L', 'XL'];


	public function init() {
		$this->item = Item::findOne(['reference' => Item::TYPE_CHROMALUXE]);
		$this->type = self::SURFACE;
		$this->w_max = Parameter::getIntegerValue('chroma_device', 'width');
		$this->h_max = Parameter::getIntegerValue('chroma_device', 'height');

		$this->prices = [];
		$this->surfaces = [];
		foreach($this->sizes as $size) {
			$this->prices[$size]   = Item::findOne(['reference' => 'Chroma'.$size]);
			$this->surfaces[$size] = Parameter::findOne(['domain' => 'formule', 'name' => 'ChromaLuxe'.$size]);
		}

		$this->min_price = $this->getPrice('ChromaMin');
		$this->inited = true;		
	}

	
	public function price($w, $h, $min = false) {
		if(!$this->inited) return 0;

		$maxlen = min($this->w_max, $this->h_max);
		if($w > $maxlen && $h > $maxlen) return;

		$s = $w * $h;

		if( $item = $this->prices[$this->getSize($s)] ) {
			//Yii::trace($w.'x'.$h.'='.$s.' < '.$p[$i]['value_number'].' i='.$i.', price='.$item->prix_de_vente);
			$price = ceil($item->prix_de_vente * $s / ($this->w_max * $this->h_max));
			return $price < $this->min_price ? $this->min_price : $price;
		}
		
		return 0; // error
	}
	

	public function getSize($s) {
		$i = 0;
		$max = count($this->sizes);
		while( ($i < $max) && ($s < $this->surfaces[$this->sizes[$max-$i-1]]->value_number) )
			$i++; 
		return $this->sizes[($i > 0 ? $max-$i : $max - 1)];
	}
	
}