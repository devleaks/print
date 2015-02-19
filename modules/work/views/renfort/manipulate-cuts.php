<?php
use app\assets\CutAsset;
use app\models\Master;
use app\models\Task;
use kartik\icons\Icon;
use kartik\slider\Slider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cuts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
CutAsset::register($this);
Icon::map($this);
?>
<div class="cut-index">

<div id="savedResults" class="alert alert-info">
</div>

<ul id="master-case">

    <?php
	foreach($masters->each() as $model) {
		echo '<li>';
		echo $this->render('_master', [
			'master' => $model,
		]);
		echo '</li>';
	}
	?>
	
	<li>
		<ul id="master-new" class="master new" data-work_length="<?= Master::DEFAULT_SIZE ?>">
		</ul>
	</li>

</ul>

<div class="row">
	<div class="col-lg-2">
		<select id="splitCut" class="form-control">
		</select>
	</div>
	<div class="col-lg-1">
		<input  type="text" id="splitSize"  class="form-control"size=="8">
	</div>
	<div class="col-lg-2">
		<a href="javascript:splitCut();" class='btn btn-success'><?= Yii::t('store', 'Split') ?></a>

		<a href="javascript:splitCut();" class='btn btn-danger'><?= Yii::t('store', 'Join') ?></a>
	</div>
</div>

<p/>	

<div>
	<a href="javascript:saveCuts();" class='btn btn-primary'><?= Yii::t('store', 'Save cuts') ?></a>

	<a href="<?= Url::to(['print-cuts'], true) ?>" class='btn btn-primary'><?= Yii::t('store', 'Print cuts') ?></a>
</div>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_CUTS'); ?>
jsonURL = {
	save: "<?= Url::to(['/work/renfort/save-cuts'], true) ?>",
	split: "<?= Url::to(['/work/renfort/split'], true) ?>"
};
js_cuts_init();
/** to change segments from masters to masters */
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_CUTS'], yii\web\View::POS_END);
