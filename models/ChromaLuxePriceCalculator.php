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
	protected $surfaces;

	public function init() {
		$this->item = Item::findOne(['reference' => Item::TYPE_CHROMALUXE]);
		$this->type = self::SURFACE;
		$this->w_max = Parameter::getIntegerValue('chroma_device', 'width');
		$this->h_max = Parameter::getIntegerValue('chroma_device', 'height');
		$this->surfaces = Parameter::find()->andWhere(['domain' => 'formule'])
							  ->andWhere(['like', 'name', 'ChromaLuxe'])
							  ->orderBy('value_number desc')
							  ->asArray()
							  ->all();
		$this->inited = true;		
	}

	
	public function price($w, $h, $min = false) {
		if(!$this->inited) return 0;

		$maxlen = min($this->w_max, $this->h_max);
		if($w > $maxlen && $h > $maxlen) return;

		$s = $w * $h;

		$i = 0;
		while($i < count($this->surfaces) && $s < $this->surfaces[$i]['value_number'])
			$i++;

		if($i > 0) $i--;
	
		if( $item = Item::findOne(['reference' => str_replace('ChromaLuxe', 'Chroma', $this->surfaces[$i]['name'])]) ) {
			//Yii::trace($w.'x'.$h.'='.$s.' < '.$p[$i]['value_number'].' i='.$i.', price='.$item->prix_de_vente);
			$price = ceil($item->prix_de_vente * $s / ($this->w_max * $this->h_max));
			$price_min = $this->getPrice('ChromaMin');
			if($price < $price_min)
				$price = $price_min;
			return $price;
		}
		
		return 0; // error
	}
}