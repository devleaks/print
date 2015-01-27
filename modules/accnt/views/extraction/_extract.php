<?php
use app\models\Client;

$client_ids = [];

foreach($models->each() as $model) {
	if( strpos($model->client->comptabilite, '??') === false && !in_array($model->client_id, $client_ids) )
		$client_ids[] = $model->client_id;
}

$clients = Client::find()->where(['id' => $client_ids])

?>
|     Popsy file

CreateKeyCustomer:Y
IgnoreAnalClosed:Y
DossierSelect:001
AcctingSelect:14

<?= $this->render('_extract_clients' , ['model' => $clients]) ?>

<?= $this->render('_extract_bills' , ['model' => $models]) ?>
