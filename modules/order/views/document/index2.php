<?php

use devleaks\metafizzy\PackeryAsset;
use app\models\Document;
use app\models\User;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

PackeryAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($document_type)) {
	$document_type = 'doc';
	$button = '<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">'.
	        	Yii::t('store', 'Create '.ucfirst(strtolower($document_type))). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
				'<li>'.Html::a(Yii::t('store', 'Enter new bid'), ['create-bid'], ['title' => Yii::t('store', 'Enter new bid')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new order'), ['create'], ['title' => Yii::t('store', 'Enter new order')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new bill'), ['create-bill'], ['title' => Yii::t('store', 'Enter new bill')]).'</li>'.
				'<li>'.Html::a(Yii::t('store', 'Enter new credit note'), ['create-credit'], ['title' => Yii::t('store', 'Enter new credit note')]).'</li>'.
			'</ul></div>';
} else
	$button = Html::a(Yii::t('store', 'Create '.ucfirst(strtolower($document_type))), ['create-'.strtolower($document_type)], ['class' => 'btn btn-success']);
			
Yii::$app->formatter->datetimeFormat = 'php:D j/n G:i';

$this->title = Yii::t('store', Document::getTypeLabel($document_type, true));
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => [User::hasRole(['manager', 'admin']) ? '/store' : '/order', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-container">

	<div id="container">
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'itemView' => function($model, $key, $index, $widget) {
				return $this->render('_doc', ['model' => $model]);
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