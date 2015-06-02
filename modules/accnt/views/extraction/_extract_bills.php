<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */
?>
<?php
	foreach($model->each() as $bill) {
		if($bill->created_at < '2015-05-31') {
			$bill->updatePrice();
		}
		echo $this->render('_extract_bill' , ['model' => $bill]);
	}
?>