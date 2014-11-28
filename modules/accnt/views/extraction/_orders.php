<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */
?>
<?php
	foreach($model->each() as $bill)
		echo $this->render('_order' , ['model' => $bill]);
?>