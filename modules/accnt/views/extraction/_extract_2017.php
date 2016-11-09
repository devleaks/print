<?php if($clients) {
	echo $this->render('_extract_clients_2017' , ['model' => $clients]);
	}
?>
<?php if($models) {
	echo $this->render('_extract_bills_2017' , ['model' => $models]);
	}
?>