<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="pics-print">
<?php foreach($order->getDocumentLines()->each() as $dl) {
		foreach($dl->getPictures()->each() as $pic) {
        	echo Html::img(Url::to($pic->getThumbnailUrl(), true));
    	}
	}
?>
</div>
