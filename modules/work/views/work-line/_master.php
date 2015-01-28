<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel devleaks\golfleague\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="master-<?= $master->id ?>" class="master" data-work_length="<?= $master->work_length ?>">
    <?php
	foreach($master->getSegments()->each() as $segment)
		echo '<li  class="segment" id="R-'.$segment->id.'" data-work_length="'.$segment->work_length.'"></li>';
	?>
</ul>
