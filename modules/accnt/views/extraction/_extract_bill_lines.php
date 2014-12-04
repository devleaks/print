<?php
?>
<?php
	foreach($model->each() as $ol)
		echo $this->render('_extract_bill_line' , ['model' => $ol]);
?>