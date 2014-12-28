<?php
$this->render('_js_load_data');
?>
<script type="text/javascript">
<?php
$this->beginBlock('JS_INIT'); ?>
console.log('setting free...');
free_item_update();
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], yii\web\View::POS_END);