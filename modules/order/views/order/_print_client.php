<?php
use yii\helpers\Html;
?>
<div class="client-print">
	<?= $model->prenom.' '.$model->nom ?><br>
	<?= $model->autre_nom ?><br>
	<?= $model->adresse ?><br>
	<?= $model->code_postal.' '.$model->localite ?><br>
	<?= ($model->pays != '' && !in_array(strtolower($model->pays), ['belgique','belgie','belgium'])) ? '<br>'.$model->pays.'<br>' : '' ?>
<br>
	<?= $model->bureau ? '<br>Bureau: '.$model->bureau : '' ?>
	<?= $model->gsm ? '<br>Mobile: '.$model->gsm : '' ?>
	<?= $model->email ? '<br>e-Mail: '.$model->email : '' ?>
</div>
