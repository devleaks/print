<?php
use yii\helpers\Url;
?>
<div class="dashboard-today">

	<h4><?= Yii::t('store', 'Today') ?></h4>

	<?= $this->render('_document', ['title' => 'Today', 'documents' => $documents]) ?>

</div>
