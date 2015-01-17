<?php

namespace app\commands;

use app\models\CuttingStock;
use yii\console\Controller;
use Yii;

class CutController extends Controller {

    public function actionIndex() {
		$blocks = [700,500,250,380];
		$quantities = [4,3,6,5];
		$max_size = 2000;
	    $cuttingStock = new CuttingStock($max_size,$blocks,$quantities);
		$i = 0;
		while($cuttingStock->hasMoreCombinations()) {
			echo "\nCombination no ".(++$i).': ';
			$map = $cuttingStock->nextCombination();
			foreach($map as $map_key => $map_value)
				echo $map_key.' * '.$map_value.', ';
		}
    }

}