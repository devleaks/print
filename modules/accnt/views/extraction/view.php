<?php

use app\models\Bill;
use app\models\Client;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExtractionLine */

$client_ids = [];
$bills = Bill::find();
foreach($bills->each() as $order)
	if(!in_array($order->client_id, $client_ids))
		$client_ids[] = $order->client_id;
$clients = Client::find()->where(['id' => $client_ids])
?>
<pre>
|     Popsy file

CreateKeyCustomer:Y
IgnoreAnalClosed:Y
DossierSelect:001
AcctingSelect:14

<?= $this->render('_clients' , ['model' => $clients]) ?>

<?= $this->render('_orders' , ['model' => $bills]) ?>
</pre>
