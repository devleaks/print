<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Backup;
use yii\console\Controller;
use Yii;

class BackupController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionCreate()
    {
        $model = new Backup();

		if($model->doBackup()) {
			if($model->save()) {
				echo Yii::t('store', 'Backup completed.');
				}
		} else {
			echo Yii::t('store', 'There was an error producing the backup.');
		}
    }
}