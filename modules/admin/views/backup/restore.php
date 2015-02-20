<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BackupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Restore Database from Backup of Production');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-index">
	
	<?php if(!file_exists($dbfile)): ?>
	<div class="alert alert-warning">
		<?= Yii::t('store', 'Database file {0} not found.', $dbfile) ?>
	</div>
	<?php endif; ?>
	
	<?php if(!file_exists($mediafile)): ?>
	<div class="alert alert-warning">
		<?= Yii::t('store', 'Media file {0} not found.', $mediafile) ?>
	</div>
	<?php endif; ?>


	<?php if(file_exists($dbfile) && file_exists($mediafile)): ?>

	<div class="alert alert-danger">
		Attention.
		En pressant ce bouton:
		<ul>
			<li>La base de donnée en cours sera complètement effacée.</li>
			<li>Toutes les images attachées aux commandes seront effacées.</li>
			<li>Tous les documents générés seront effacés.</li>
			<li>La base de donnée dans le répertoire de restauration sera installée.</li>
			<li>Toutes les images attachées aux commandes restaurées seront copiées.</li>
			<li>Tous les documents générés dans la base de données restaurée seront copiés.</li>
		</ul>
	</div>

    <?php $form = ActiveForm::begin(['action' => Url::to(['/admin/backup/do-restore'])]); ?>

    <?= Html::submitButton('<i class="glyphicon glyphicon-warning-sign"></i> '.Yii::t('store', 'Restore Database from Backup of Production'), ['class' => 'btn btn-lg btn-danger']) ?>

    <?php ActiveForm::end(); ?>

	<?php endif; ?>

</div>
