<?php
?>
<?php
	foreach($model->each() as $ol)
		echo $this->render('_order_line' , ['model' => $ol]);
?>