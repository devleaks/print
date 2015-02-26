<?php

use app\assets\PrintAsset;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
PrintAsset::register($this);
?>
<div class="order-print">

	<?php
	  	echo $this->render('@app/modules/store/prints/common/header-web');
		echo $this->render('@app/modules/store/prints/document/body', ['model' => $model, 'images' => true]);
	    echo $this->render('@app/modules/store/prints/common/footer');
	?>

</div>
