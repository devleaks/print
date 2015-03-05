<?php
use app\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

$done = false;
$errors = '';

foreach($clients->each() as $client) {
	if(!$done) {
		$errors .= Yii::t('store', 'Client(s) with no POPSY reference:').'</br><ul>';
		$done = true;
	}
	$errors .=  '<li>'.Html::a($client->nom, Url::to(['/store/client/view', 'id' => $client->id]), ['target' => '_blank']).
				' '.Yii::t('store', 'for').' '.
				Html::a(Yii::t('store', $baddocscli[$client->id]->document_type).' '.$baddocscli[$client->id]->name, Url::to(['/order/document/view', 'id' => $baddocscli[$client->id]->id]), ['target' => '_blank']).'</li>';
}
if($done)
	$errors .=  '</ul>';

$done = false;

foreach($models as $doc) {
	if(!$done) {
		$errors .=  Yii::t('store', 'Bill(s) with item(s) with no POPSY reference:').'</br><ul>';
		$done = true;
	}
	$errors .=  '<li>'.Html::a(Yii::t('store', $doc->document_type).' '.$doc->name, Url::to(['/order/document/view', 'id' => $doc->id]), ['target' => '_blank']).'</li>';
}
if($done)
	$errors .=  '</ul>';

?>

<?php if($errors != ''): ?>
<div class="alert alert-danger" role="alert">
	<?= $errors ?>
</div>
<?php endif; ?>
