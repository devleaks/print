<?php

use dosamigos\gallery\Gallery;

?>
<div class="order-line-picture">

<?php
    $pics = array();
    foreach($model->getOrder()->one()->getOrderLines()->each() as $ol)
    	foreach($ol->getPictures()->all() as $picture) {
	        $pics[] = [
	            'url' => $picture->getUrl(),
	            'src' => $picture->getThumbnailUrl(),
	            'options' => array('title' => $picture->name)
	        ];
	    }
    echo Gallery::widget(['items' => $pics]);
?>

</div>