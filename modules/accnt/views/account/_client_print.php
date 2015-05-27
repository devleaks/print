<?php

use app\models\Parameter;
use app\models\User;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="account-index">

<h2><?= Yii::t('store', 'Account Status for {0}', $client->nom) ?></h2>

<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Created At') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Debit') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Credit') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Note') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Solde') ?></th>
	</tr>
	</thead>
	<tbody>
<?php
	foreach($allModels as $model): ?>
	<tr>
		<td style="text-align: center;"><?= Yii::$app->formatter->asDate($model->date) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->amount < 0 ? $model->amount : '') ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->amount > 0 ? $model->amount : '') ?></td>
		<td ><?= $model->note ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->account) ?></td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="4"></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($bottomLine) ?></th>
	</tr>
	</tfoot>
</table>

</div>
