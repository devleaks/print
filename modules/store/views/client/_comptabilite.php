<?php
use yii\helpers\Url;
?>
<script type="text/javascript">
<?php $this->beginBlock('JS_COMPTAUNIQ'); ?>
$("#client-nom").change(function() {
	ident = $("#client-comptabilite").val();
	//console.log('cur val='+ident);
	if(ident == '') {		
		s = $("#client-nom").val();
		//console.log('client='+s);
		$.ajax({
			type: "GET",
			url: "<?= Url::to(['/store/client/get-unique-identifier'], true) ?>",
			dataType: 'json',
			async: !1,
			data: {
				s: s
			},
			success: function(data) {
				ident = data.result;
			},
			error: function(data) {
				console.log('JS_COMPTAUNIQ: error '+s);
			},
		});
		$("#client-comptabilite").val(ident);
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_COMPTAUNIQ'], yii\web\View::POS_END);