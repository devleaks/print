<?php

use devleaks\metafizzy\PackeryAsset;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

PackeryAsset::register($this);

$this->title = Yii::t('store', 'Représentation graphique de toutes les tailles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;


// @todo: Use pagination, and perform a infinite scroll on pagination
$dataProvider->pagination = false;

?>
<div class="jjm-graphic">

    <h1><?= Html::encode($this->title) ?><span style="font-size: 14px;">
	<?php for($i = 2014; $i <= date('Y'); $i++)
			echo ' » '.Html::a($i, Url::to(['bricks', 'year' => $i]), ['title' => Yii::t('store', 'View Frames in '.$i)]);
	?>
	</span></h1>

<div id="container">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => function($model, $key, $index, $widget) {
			return $this->render('_frame', ['model' => $model]);
		},
		'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
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
/**

$(function(){
  
  var $container = $('#container');
  
  $container.imagesLoaded(function(){
    $container.masonry({
      itemSelector: '.box',
      columnWidth: 100
    });
  });
  
  $container.infinitescroll({
    navSelector  : '#page-nav',    // selector for the paged navigation 
    nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
    itemSelector : '.box',     // selector for all items you'll retrieve
    loading: {
        finishedMsg: 'No more pages to load.',
        img: 'http://i.imgur.com/6RMhx.gif'
      }
    },
    // trigger Masonry as a callback
    function( newElements ) {
      // hide new items while they are loading
      var $newElems = $( newElements ).css({ opacity: 0 });
      // ensure that images load before adding to masonry layout
      $newElems.imagesLoaded(function(){
        // show elems now they're ready
        $newElems.animate({ opacity: 1 });
        $container.masonry( 'appended', $newElems, true ); 
      });
    }
  );
  
});

**/