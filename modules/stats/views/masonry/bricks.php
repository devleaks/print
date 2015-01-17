<?php

use app\assets\PackeryAsset;
use yii\widgets\ListView;
use yii\helpers\Url;

PackeryAsset::register($this);

$this->title = Yii::t('store', 'Packery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination = false;

?>
<div id="container">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => function($model, $key, $index, $widget) {
			return $this->render('_to-cut', ['model' => $model]);
		},
	])
	?>
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_PACKERY'); ?>
$container = $("#container");
$container.packery({
  itemSelector: '.item',
});
$container.find('.item').each( function( i, itemElem ) {
  // make element draggable with Draggabilly
  var draggie = new Draggabilly( itemElem );
  // bind Draggabilly events to Packery
  $container.packery( 'bindDraggabillyEvents', draggie );
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PACKERY'], yii\web\View::POS_END);