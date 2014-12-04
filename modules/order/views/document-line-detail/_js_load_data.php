<?php
use app\assets\ItemAsset;
use app\models\Item;
use app\models\Parameter;
use yii\helpers\Url;

ItemAsset::register($this);


$chroma_item = Item::findOne(['reference'=>'1']);
$fineart_item = Item::findOne(['reference'=>'FineArts']);
$free_item = Item::findOne(['reference'=>Item::TYPE_FREE]);
$class_prefix = 'item';

?>
<script type="text/javascript">
<?php
$items = [];
foreach(Item::find()->where(['identification' => 'YII'])->each() as $item)
	$items[$item->reference] = [
		'id' => $item->id,
		'reference' => $item->reference,
		'libelle' => $item->libelle_long,
		'price' => $item->prix_de_vente,
		'vat' => $item->taux_de_tva,
		'manufacturer' => $item->fournisseur,
		'data' => $item->quantite,
	];
$js_items = json_encode($items);

$params = [];
foreach(Parameter::find()->where(['domain' => 'formule'])->each() as $param)
	$params[$param->name] = [
		'value_int' => $param->value_int,
		'value_number' => $param->value_number,
		'value_text' => $param->value_text,
	];
$js_params = json_encode($params);

$this->beginBlock('JS_ITEM') ?>

var store_values = {
	item_id: {
		chroma: <?= $chroma_item->id ?>,
		fineart: <?= $fineart_item->id ?>,
		freeitem: <?= $free_item->id ?>
	},
	class_prefix: "<?= $class_prefix ?>",
	ajaxUrl: "<?= Url::to(['/order/document/get-item'], true) ?>"
};

var items  = <?= $js_items  ?>;
var params = <?= $js_params ?>;

<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_ITEM'], yii\web\View::POS_BEGIN);
