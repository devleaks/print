<?php

use yii\helpers\Url;
use app\models\Document;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div id="today-doc" class="dashboard-work-line">

<table class="table">
	<?php
	echo '<thead><tr>';
	echo '<th>'.Yii::t('store', 'Documents').'</th>';
	foreach([Document::STATUS_OPEN, Document::STATUS_TODO, Document::STATUS_BUSY, Document::STATUS_DONE, Document::STATUS_TOPAY] as $dt) {
		echo '<th>'.Yii::t('store', $dt).'</th>';
	}		
	echo '</tr></thead><tbody>';
	foreach([Document::TYPE_TICKET, Document::TYPE_ORDER, Document::TYPE_BILL] as $dt) {
		echo '<tr>';
		echo '<th>'.Yii::t('store', $dt).'</th>';
		foreach([Document::STATUS_OPEN, Document::STATUS_TODO] as $st) {
			echo isset($documents[$dt][$st]) ?
				'<td style="text-align: center;">'.$documents[$dt][$st].'</td>'
				: '';
		}		
		echo '</tr>';
	}
	echo '</tbody>';
	?>
</table>

Travaux non terminés: <?= $works->count() ?>.

</div>