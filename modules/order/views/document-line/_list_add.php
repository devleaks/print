<?php

use app\models\DocumentLine;
use app\models\DocumentLineSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="document-line-detail">

<?php if($order->getDocumentLines()->count() > 0): ?>

	<?= $this->render('_list', [
			'dataProvider' => new ActiveDataProvider([
				'query' => $order->getDocumentLines(),
			]),
			'order' => $order,
			'action_template' => '{view} {update} {delete}'
		])
	?>
<?php else: ?>
	<br/><br/>
<?php endif; ?>

<?php if(!in_array($order->document_type, [$order::TYPE_CREDIT,$order::TYPE_REFUND])) {
		if(!isset($orderLine)) {
		 	$orderLine = new DocumentLine();
			$orderLine->document_id = $order->id;
		}

		echo $this->render('_add', [
			'model' => $orderLine,
			'order' => $order,
			'form'	=> $form,
		]);
	}
?>

</div>
