<?php
use yii\helpers\Html;
?>
<div class="print-client">
	<span style='font-weight: bold;'><?= $model->prenom.' '.$model->nom ?></span><br>
	<?= $model->autre_nom ?><br>
	<?= $model->adresse ?><br>
	<?= $model->code_postal.' '.$model->localite ?><br>
	<?= ($model->pays != '' && !in_array(strtolower($model->pays), ['belgique','belgie','belgium'])) ? '<br>'.$model->pays.'<br>' : '' ?>
</div>
