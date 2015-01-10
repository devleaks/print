<?php

namespace app\components;

use Yii;

class RuntimeDirectoryManager {
	/** */
	const PATH_LATE_BILLS = 'document/late-bills';
	/** */
	const PATH_ACCOUNT_SLIP = 'document/account';
	/** */
	const PATH_DAILY_REPORT = 'daily';
	/** */
	const PATH_PICTURES = 'pictures';
	/** */
	const PATH_EXTRACTION = 'extraction';
	/** */
	const PATH_BACKUP = 'backup';
	/** */
	const PATH_DOCUMENT = 'document';
	
	public function getPath($for) {
		if($for == self::PATH_PICTURES)
			$dirname = Yii::$app->params['picturePath'];
		else
			$dirname = Yii::getAlias('@runtime').'/'.$for.'/';

		if(!is_dir($dirname))
		    if(!mkdir($dirname, 0777, true))
				return null;

		return $dirname;
	}
}