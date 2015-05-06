<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for software update.
 *
 */
class Update extends Model
{
	const LATEST = 'Latest';
	
	/**
	 * @param string $revision Revision to restore. Latest if null.
	 */
	public static function executeUpdate($revision = null) {
		$UPDATE = Yii::getAlias('@app')."/runtime/etc/test.sh";
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin
		   1 => array("pipe", "w"),  // stdout
		   2 => array("pipe", "w"),  // stderr
		);
		/*
		$process = proc_open($UPDATE, $descriptorspec, $pipes, Yii::getAlias('@app'), null);
		$stdout = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		$content = print_r($stdout, true) + print_r($stderr, true);
		file_put_contents('/tmp/yii2-print.txt', $content);
		$status = proc_close($process);
		$command = $UPDATE." 2>&1 > /tmp/yii2-print.txt";
//		system($command, $status);
		Yii::trace($command.': '.$status, 'Update::executeUpdate');
		return $status;
		*/
	}
	
}
