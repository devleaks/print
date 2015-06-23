<?php

use app\models\Account;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="daily-summary-detail">

<h4><?= $label ?></h4>

<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Transaction') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Date') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Amount') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Solde') ?></th>
	</tr>
	<tr>
		<td colspan="3"></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($cash_start) ?></td>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	$solde = 0;
	foreach($dataProvider->allModels as $model): ?>
	<tr>
		<td><?php
			if($model->ref != null) {
				if($account = Account::findOne(['cash_id' => $model->ref]))
            		echo $account->client->nom;
				else
					echo '';
			} else {
				echo $model->note;
			}
		?></td>
		<td style="text-align: center;"><?= $model->date ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->amount) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->solde) ?></td>
		<?php $tot_amount += $model->amount; $solde = $model->solde; ?>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="2"></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($solde) ?></th>
	</tr>
	</tfoot>
</table>
</div>
