<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="bill-line-print">
<table width="100%" class="table table-bordered">
	<thead>
	<tr>
	<?php if($images): ?>
		<th style="text-align: center;"><?= Yii::t('print', 'Document') ?></th>
	<?php endif; ?>
		<th style="text-align: center;"><?= Yii::t('print', 'Ref.') ?></th>
		<th style="text-align: left;"><?= Yii::t('print', 'Item')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Qty')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Price')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Extra')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Total')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'VAT')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($dataProvider->query->each() as $model): ?>
	<tr>
		<?php if($images): ?>
			<td style="text-align: center;">
				<?php if($pic = $model->getPictures()->one()) 
					echo Html::img(Url::to($pic->getThumbnailUrl(), true));
				?>
			</td>
		<?php endif; ?>
		<td><?= $model->item->reference ?></td>
		<td><?= $model->getDescription() ?></td>
		<td style="text-align: center;"><?= $model->quantity ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->unit_price) ?></td>
		<td><?= $model->getExtraDescription(false) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency(round($model->price_htva + $model->extra_htva, 2)) ?></td>
		<td style="text-align: center;"><?= $model->vat.'&nbsp;%' ?></td>
		<?php $tot_amount += round($model->price_htva + $model->extra_htva, 2); ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="5"></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
</div>
