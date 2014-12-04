<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\gallery\Gallery;
use kartik\icons\Icon;

Icon::map($this);

?>
<div class="picture-update">

<?php
    foreach($model->getPictures()->all() as $picture) {
		echo Html::img($picture->getThumbnailUrl());
		echo Html::a(Icon::show('remove'), Url::to(['delete-picture', 'id' => $picture->id]), ['class' => 'btn btn-warning']);
		echo '&nbsp;';
    }
?>

</div>