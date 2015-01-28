<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\TouchSpin;

$magnify = 3;
$classes = [];
/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="master-cut">

	<div class="renfort-master" style="width: <?= $magnify * $model->work_length + 10 ?>px;">
    <?php
    foreach($model->getSegments()->each() as $segment) {
		$w = $magnify * $segment->work_length;
		$t = '1em'; // $segment->work_length < 40 ?  ($segment->work_length / 40).'em' : '1em';
		$class = 'renfort-'.$segment->document_line_id;
		$classes[] = $class;
		echo '<div class="renfort-cut '.$class.'" style="width: '.$w.'px;font-size:'.$t.';">'.$segment->documentLine->document->name.'</div>';
	}
    ?>
	</div>
</div>
<?php
$this->beginBlock('JS_HIGHLIGHT_CUTS');
foreach($classes as $class) : ?>
$(".<?= $class ?>").hover(
       function(){ $(".<?= $class ?>").addClass('renfort-highlight') },
       function(){ $(".<?= $class ?>").removeClass('renfort-highlight') }
);
<?php endforeach;
$this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_HIGHLIGHT_CUTS'], yii\web\View::POS_END);
