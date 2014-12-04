<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */
?>
<?php
	foreach($model->each() as $client)
		echo $this->render('_extract_client' , ['model' => $client]);
?>