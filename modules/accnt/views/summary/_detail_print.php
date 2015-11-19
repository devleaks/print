<?php

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
			if($payment = Payment::findOne(['account_id' => $model->id])) {
				if($doc = Document::find()->andWhere(['sale' => $payment->sale])->orderBy('created_at desc')->one())
					echo $doc->name;
			}
		?></td>
		<td style="text-align: center;"><?= $model->created_at ?></td>
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
