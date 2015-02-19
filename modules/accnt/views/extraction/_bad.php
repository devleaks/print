<?php
use app\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

$done = false;
?>
<div class="alert alert-danger" role="alert">

<?php
foreach($clients->each() as $client) {
	if(!$done) {
		echo Yii::t('store', 'Client(s) with no POPSY reference:').'</br><ul>';
		$done = true;
	}
	echo '<li>'.Html::a($client->nom, Url::to(['/store/client/view', 'id' => $client->id]), ['target' => '_blank']).
				' '.Yii::t('store', 'for').' '.
				Html::a(Yii::t('store', $baddocscli[$client->id]->document_type).' '.$baddocscli[$client->id]->name, Url::to(['/order/document/view', 'id' => $baddocscli[$client->id]->id]), ['target' => '_blank']).'</li>';
}
echo '</ul>';
$done = false;

foreach($models as $doc) {
	if(!$done) {
		echo Yii::t('store', 'Bill(s) with item(s) with no POPSY reference:').'</br><ul>';
		$done = true;
	}
	echo '<li>'.Html::a(Yii::t('store', $doc->document_type).' '.$doc->name, Url::to(['/order/document/view', 'id' => $doc->id]), ['target' => '_blank']).'</li>';
}
echo '</ul>';
?>
</div>