<?php
use app\assets\ItemAsset;
use app\models\Item;
use app\models\Parameter;
use yii\helpers\Url;

ItemAsset::register($this);

/** Error Messages */
$errors = [];
$errors["CHROMALUXE_TYPE"] = Yii::t('store','You must specify the type of ChromaLuxe.');
$errors["FINEART_NO_TIRAGE"] = Yii::t('store','You must enter a type of print.');
$errors["FREEITEM_NO_DESCRIPTION"] = Yii::t('store','You must enter a description for the item.');
$errors["FREEITEM_NO_PRICE"] = Yii::t('store','You must enter a price (or 0) for the item.');
$errors["ITEM_NOT_FOUND"] = Yii::t('store','Item not found.');
$errors["NO_WORK_SIZE"] = Yii::t('store','You must enter width and height.');
$errors["SURFACE_TOO_LARGE"] = Yii::t('store','Surface too large.');
$errors["WORK_TOO_LARGE"] = Yii::t('store','Width or height too large.');
$js_errors = json_encode($errors);


/** Application Parameters */
$params = [];
foreach(Parameter::find()->where(['domain' => 'formule'])->each() as $param)
	$params[$param->name] = [
		'value_int'    => $param->value_int,
		'value_number' => $param->value_number,
		'value_text'   => $param->value_text,
	];
$js_params = json_encode($params);

$items = [];
$item_refs = [];
foreach(Item::find()->where(['NOT',['yii_category' => null]])->each() as $item) {
	$items[$item->id] = [
		'id'            => $item->id,
		'reference'     => $item->reference,
		'yii_category'  => $item->yii_category,
		'libelle_long'  => $item->libelle_long,
		'prix_de_vente' => $item->prix_de_vente,
		'taux_de_tva'   => $item->taux_de_tva,
		'fournisseur'   => $item->fournisseur,
	];
	$item_refs[$item->reference] = $item->id;
}
$js_items = json_encode($items);
$js_itemrefs = json_encode($item_refs);


?>
<script type="text/javascript">
<?php
$this->beginBlock('JS_ITEM') ?>
var store_values = {
	chroma: <?= Item::findOne(['reference'=>Item::TYPE_CHROMALUXE])->id ?>,
	param: <?= $js_params ?>,
	item: <?= $js_items ?>,
	item_ref: <?= $js_itemrefs ?>,
	error_msg: <?= $js_errors ?>,
	ajaxUrl: "<?= Url::to(['/order/document-line/item-price'], true) ?>"
};
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_ITEM'], yii\web\View::POS_BEGIN);