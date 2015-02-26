<?php

use devleaks\metafizzy\PackeryAsset;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

PackeryAsset::register($this);

$this->title = Yii::t('store', 'Représentation graphique de toutes les tailles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination = false;

?>
<div class="jjm-graphic">

    <h1><?= Html::encode($this->title) ?></h1>

<div id="container">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => function($model, $key, $index, $widget) {
			return $this->render('_frame', ['model' => $model]);
		},
	])
	?>
</div>

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