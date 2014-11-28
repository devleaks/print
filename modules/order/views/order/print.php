<?php

use app\assets\PrintAsset;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

PrintAsset::register($this);
?>
<div class="order-print">

	<?= $this->render('_print', [
			'model' => $model,
	    ]);
	?>

</div>
