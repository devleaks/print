<?php

use app\models\Document;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* */
?>
<div class="item">
	<?php
	if($picture = $model->getPictures()->one())
		echo Html::img($picture->getThumbnailUrl(), ['alt'=>$picture->name, 'title'=>$picture->name]);
	else
		echo Html::img('http://placehold.it/128x128')
	?>
	<div>
		<?= Html::a($model->name, Url::to(['/order/document/view', 'id' => $model->id])) ?>
	</div>
</div>