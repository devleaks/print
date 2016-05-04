<?php

use app\models\Account;
use app\models\Document;
use app\models\Payment;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="daily-summary-detail">

	<h4><?= $label ?></h4>

<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Client') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Order') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Date') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Amount') ?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($dataProvider->query->each() as $model): ?>
	<tr>
		<td><?php
			if($client = $model->client)
        		echo $client->nom;
			else
				echo '';
		?></td>
		<td><?php
			if($account = Account::findOne($model->id)) {
				return $account->whatFor();
			} else
    			return '';
		?></td>
		<td style="text-align: center;"><?= Yii::$app->formatter->asDate($model->payment_date) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->amount) ?></td>
		<?php $tot_amount += $model->amount; ?>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="3"></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
	</tr>
	</tfoot>
</table>
</div>
