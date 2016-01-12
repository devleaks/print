<?php
use app\models\Parameter;

$year = null;
if($model = $models->one()) {
	$year = substr($model->name, 2, 2);
} else {
	$year = date('y');
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

<?= $this->render('_extract_clients' , ['model' => $clients]) ?>

<?= $this->render('_extract_bills' , ['model' => $models]) ?>
