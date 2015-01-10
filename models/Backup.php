<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

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

	public function doBackup() {
		$dsn = $this->getDb()->dsn;
		$db  = Backup::parseDSN($dsn);
		$dbhost = $db['host'];
		$dbname = $db['dbname'];
		$dbuser = $this->getDb()->username;
		$dbpass = $this->getDb()->password;

		$backup_file = $dbname . date("Y-m-d-H-i-s") . '.gz';
		$backup_dir  = Yii::getAlias('@runtime') . '/backup/';
		if(!is_dir($backup_dir))
			mkdir($backup_dir);
			
		$command = "/Applications/mampstack/mysql/bin/mysqldump --opt -h $dbhost -u $dbuser -p$dbpass ".$dbname.
		           "| gzip > ". $backup_dir . $backup_file;

		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');

		if($status == 0) { // ok
			$this->filename = $backup_file;
			$this->status = 'OK';
		}
		return ($status == 0);
	}
	
	public function delete() {
		$backup_file = Yii::getAlias('@runtime') . '/backup/' . $this->filename;
		if(is_file($backup_file))
			unlink($backup_file);
		parent::delete();
	}

}
