<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to compute dimension-based item prices.
 */
class ExhibitPriceCalculator extends LinearRegressionPriceCalculator
{

	public function price($w2, $h2) {
		$w = max($w2, 30);
		$h = max($h2, 30);
		return parent::price($w, $h);
	}
}