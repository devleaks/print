<?php
use app\models\Parameter;

// switch will be removed when transition is over
if(Parameter::isTrue('application', 'new_accounting')) {
	echo $this->render('_extract_2017' , ['models' => $models, 'clients' => $clients]);
	return;
}

$year = date('y');
if(($models != null) && ($model = $models->one())) {
	$year = substr($model->name, 2, 2);
}
$ver = Parameter::isTrue('popsy', 'extract_version') ? ' Rel. '.`git describe --tags` : '
';
?>
|     Popsy file<?= $ver
?>
CreateKeyCustomer:Y
IgnoreAnalClosed:Y
DossierSelect:001
AcctingSelect:<?= $year ?>

<?php if($clients != null) echo $this->render('_extract_clients' , ['model' => $clients]) ?>

<?php if($models != null)  echo $this->render('_extract_bills' , ['model' => $models]) ?>
