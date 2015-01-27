<?php
use app\models\Client;

$client_ids = [];
$str = '';
foreach($models->each() as $model) {
	if( strpos($model->client->comptabilite, '??') === 0 && !in_array($model->client_id, $client_ids) ) {
		$client_ids[] = $model->client_id;
		if($str == '') $str .= Yii::t('store', 'Client with no POPSY reference:').'</br><ul>';
		$str .= '<li>'.$model->client->nom.' for '.Yii::t('store', $model->document_type).' '.$model->name.'</li>';
	}
}
if($str != '')
	echo '<div class="alert alert-danger" role="alert">'.$str.'</ul></div>';