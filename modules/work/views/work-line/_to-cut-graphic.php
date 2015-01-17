<?php
use app\models\Item;
use yii\helpers\Url;

$chroma_item  = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE]);
?>
<div class="item <?= ($model->item_id == $chroma_item->id ? 'item_orange' : 'item_green') ?>" style="width: <?= $model->work_width ?>px; height: <?= $model->work_height ?>px;" title="<?= $model->document->name ?>">
	<?= $model->work_width.' &times; '.$model->work_height.'<br/>'.$model->quantity.'<br/>'.$model->item_id?>
</div>