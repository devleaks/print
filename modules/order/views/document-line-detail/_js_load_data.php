<?php
use app\assets\ItemAsset;
use app\models\Item;
use app\models\Parameter;
use yii\helpers\Url;

ItemAsset::register($this);


/** Specail Items */
$chroma_item  = Item::findOne(['reference'=>Item::TYPE_CHROMALUXE]);
$fineart_item = Item::findOne(['reference'=>Item::TYPE_FINEARTS]);
$free_item    = Item::findOne(['reference'=>Item::TYPE_FREE]);
$class_prefix = 'item';


/** Error Messages */
$errors = [];
$errors["SURFACE_TOO_LARGE"] = Yii::t('store','Surface too large.');
$errors["CHROMALUXE_TYPE"] = Yii::t('store','You must specify the type of ChromaLuxe.');
$errors["ITEM_NOT_FOUND"] = Yii::t('store','Item not found.');
$errors["WORK_TOO_LARGE"] = Yii::t('store','Width or height too large.');
$errors["NO_WORK_SIZE"] = Yii::t('store','You must enter width and height.');
$errors["FREEITEM_NO_PRICE"] = Yii::t('store','You must enter a price (or 0) for the item.');
$errors["FREEITEM_NO_DESCRIPTION"] = Yii::t('store','You must enter a description for the item.');
$errors["FINEART_NO_TIRAGE"] = Yii::t('store','You must enter a type of print.');
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


?>
<script type="text/javascript">
<?php
$this->beginBlock('JS_ITEM') ?>
var store_values = {
	item_id: {
		chroma: <?= $chroma_item->id ?>,
		fineart: <?= $fineart_item->id ?>,
		freeitem: <?= $free_item->id ?>
	},
	param: <?= $js_params ?>,
	error_msg: <?= $js_errors ?>,
	class_prefix: "<?= $class_prefix ?>",
	ajaxUrl: "<?= Url::to(['/order/document/get-item'], true) ?>"
};
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_ITEM'], yii\web\View::POS_BEGIN);