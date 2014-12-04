<?php

use app\models\Bill;
use app\models\Client;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */

$bills = Bill::find();

$client_ids = [];
foreach($bills->each() as $bill)
	if(!in_array($bill->client_id, $client_ids))
		$client_ids[] = $bill->client_id;
$clients = Client::find()->where(['id' => $client_ids])
?>
<pre>
|     Popsy file

CreateKeyCustomer:Y
IgnoreAnalClosed:Y
DossierSelect:001
AcctingSelect:14

<?= $this->render('_extract_clients' , ['model' => $clients]) ?>

<?= $this->render('_extract_bills' , ['model' => $bills]) ?>
</pre>
