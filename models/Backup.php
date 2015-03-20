<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\RuntimeDirectoryManager;

/**
 * This is the model class for table "backup".
 *
 * @property integer $id
 * @property string $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Backup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backup';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//			[['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 250],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'filename' => Yii::t('store', 'Filename'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }

	/**
	 *		mysql:host=localhost;port=3307;dbname=testdb
	 *		mysql:unix_socket=/tmp/mysql.sock;dbname=testdb
	 */
	public static function parseDSN($dsn) {
		$db = [];
		foreach(explode(';', str_replace('mysql:', '', $dsn)) as $e) {
			$a = explode('=', $e);
			$db[$a[0]] = $a[1];
		}
		return $db;
	}


	/**
	 *		mysql:host=localhost;port=3307;dbname=testdb
	 *		mysql:unix_socket=/tmp/mysql.sock;dbname=testdb
	 */
	public static function getDbName($name) {
		$db  = Backup::parseDSN(Yii::$app->getDb()->dsn);
		return $name ? $name == $db['dbname'] : $db['dbname'];
	}


	protected function executeBackup($full, $uniq) {
		$now = date("Y-m-d-H-i-s");
		$dsn = $this->getDb()->dsn;
		$db  = Backup::parseDSN($dsn);
		$dbhost = $db['host'];
		$dbname = $db['dbname'];
		$dbuser = $this->getDb()->username;
		$dbpass = $this->getDb()->password;

		$backup_dir  = RuntimeDirectoryManager::getBackupRoot();
			
		// Database
		$mylsqldump = Yii::$app->params['mysql_home'] . 'bin/mysqldump';
		$db_backup_file = RuntimeDirectoryManager::getFilename($uniq ? RuntimeDirectoryManager::BACKUP : RuntimeDirectoryManager::BACKUP1, $dbname);
		$command = $mylsqldump . " --opt -h $dbhost -u $dbuser -p$dbpass ".$dbname.
		           "| gzip > ". $backup_dir . $db_backup_file;
		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');

		if($full) {	// Media
			$media_backup_file = RuntimeDirectoryManager::getFilename($uniq ? RuntimeDirectoryManager::BACKUP_MEDIA : RuntimeDirectoryManager::BACKUP_MEDIA1, $dbname);
			$command = "(cd ".RuntimeDirectoryManager::getFileStoreDirectory().
				" ; tar czf ".$backup_dir . $media_backup_file." ".RuntimeDirectoryManager::FILESTORE_PICTURES." ".RuntimeDirectoryManager::FILESTORE_DOCUMENTS.")";
			system($command, $status);
			Yii::trace($command.': '.$status, 'BackupController::doBackup');
		}

		if($status == 0) { // ok
			$this->filename = $db_backup_file;
			$this->status = 'OK';
		}
		return ($status == 0);
	}
	
	public function doBackup($uniq = true) {
		return $this->executeBackup(false, $uniq);
	}

	public function doFullBackup($uniq = true) {
		return $this->executeBackup(true, $uniq);
	}

	public function delete() {
		$backup_file = RuntimeDirectoryManager::getBackupRoot() . $this->filename;
		if(is_file($backup_file))
			unlink($backup_file);
		parent::delete();
	}
	
	public static function restore() {
		$logfile = Yii::getAlias('@runtime').'/logs/restore.log';
		$command = Yii::getAlias('@runtime').'/etc/restore.sh 2>&1 > '.$logfile;
		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');
		return $status == 0;
	}

}
