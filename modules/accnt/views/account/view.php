<?php

use app\models\Document;

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
$docs = [];
$id = false;
foreach($model->getDocuments()->each() as $doc) {
	$docs[] = $doc->name;
	$id = $doc->id;
}
if($id)
	$this->params['breadcrumbs'][] = ['label' => implode($docs, ','), 'url' => ['/accnt/payment/sale', 'id' => $id]];
$this->title = Yii::t('store', 'Account {0} for {1}', [$model->id, implode($docs, ',')]);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'client.nom',
            'amount',
            'payment_date',
            'payment_method',
            'note',
            'status',
            'created_at',
            'createdBy.username',
            'updated_at',
            'updatedBy.username',
        ],
    ]) ?>

	<?= $this->render('../payment/_account.php', [
		'dataProvider' => new ActiveDataProvider(['query' => $model->getPayments()]),
		'model' => $model,
	])?>

</div>
