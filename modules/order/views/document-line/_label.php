<?php

use yii\data\ActiveDataProvider;
$lang = ($model->document->client->lang ? $model->document->client->lang : 'fr');
Yii::$app->language = $lang;
?>
<div class="document-line-label">

	<?= $this->render('_label_print', [
			'model' => $model,
			'picture' => $picture,
	    ])
	?>

</div>
