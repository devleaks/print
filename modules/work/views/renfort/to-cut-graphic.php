<?php

use devleaks\metafizzy\PackeryAsset;
use devleaks\metafizzy\DraggabillyAsset;
use yii\widgets\ListView;
use yii\helpers\Url;

PackeryAsset::register($this);
DraggabillyAsset::register($this);

$this->title = Yii::t('store', 'Cuts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination = false;

?>
<div id="container">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => function($model, $key, $index, $widget) {
			return $this->render('_to-cut-graphic', ['model' => $model->documentLine]);
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