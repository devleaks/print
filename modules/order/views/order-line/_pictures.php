<?php

use dosamigos\gallery\Gallery;

?>
<div class="picture-gallery">

<?php
    $pics = array();
    foreach($model->getPictures()->all() as $picture) {
        $pics[] = [
            'url' => $picture->getUrl(),
            'src' => $picture->getThumbnailUrl(),
            'options' => array('title' => $picture->name)
        ];
    }
    echo Gallery::widget(['items' => $pics]);
?>

</div>