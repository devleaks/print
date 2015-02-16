<?php

namespace app\commands;

use app\models\Backup;
use yii\console\Controller;
use Yii;

class BackupController extends Controller {
	/**
	 *  Create performs a mysql database backup.
	 *
	 */
    public function actionCreate($uniq = true) {
        $model = new Backup();

		if($model->doBackup($uniq != 'false')) {
			if($model->save()) {
				echo Yii::t('store', 'Backup completed.');
				}
		} else {
			echo Yii::t('store', 'There was an error producing the backup.');
		}
    }

    public function actionFull($uniq = true) {
        $model = new Backup();

		if($model->doFullBackup($uniq != 'false')) {
			if($model->save()) {
				echo Yii::t('store', 'Backup completed.');
				}
		} else {
			echo Yii::t('store', 'There was an error producing the backup.');
		}
    }

	/**
	 *  Deletes all backup older than given days.
	 *
	 *	@param integer $days Number of days to keep backup. Must be larger than 7. Defaults to 7.
	 */
    public function actionDelete($days = 7) {
		if(intval($days)<7) $days = 7;
		$last = date('Y-m-d', strtotime($days.' days ago'));
		foreach(Backup::find()->where(['<=','created_at',$last])->each() as $backup)
			$backup->delete();
		echo Yii::t('store', 'Backup older than {0} deleted.', [$last]);
    }

}