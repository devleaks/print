<?php
/**
 *	RuntimeDirectoryManager class serves directory and file names for documents generated from the application.
 *
 */
namespace app\components;

use Yii;
use yii\helpers\Url;

class RuntimeDirectoryManager {
	/**
	 *	Categories of generated documents.
	 *	Each category is stored in its own directory.
	 *
	 */

	/** */
	const FILESTORE_DOCUMENTS = 'documents';
	/** */
	const FILESTORE_PICTURES = 'pictures';
	/** */
	const FILESTORE_BACKUPS = 'backup';
	/** */
	const BACKUP = 'BACKUP';
	/** */
	const BACKUP_MEDIA = 'BACKUP_MEDIA';
	/** */
	const BACKUP1 = 'BACKUP1';
	/** */
	const BACKUP_MEDIA1 = 'BACKUP_MEDIA1';
	/** */
	const DAILY_REPORT = 'DAILY_REPORT';
	/** */
	const EXTRACTION = 'EXTRACTION';
	/** */
	const DOCUMENT = 'DOCUMENT';
	/** */
	const PICTURES = 'PICTURES';
	/** */
	const LATE_BILLS = 'LATE_BILLS';
	/** */
	const ACCOUNT = 'ACCOUNT_SLIP';
	/** */
	const FRAME_ORDERS = 'FRAME_ORDERS';
	
	
	/** Templates */
	protected static $TEMPLATE = [
		self::LATE_BILLS => 'document/late-bill/{client:name}-{name}',
		self::ACCOUNT => 'document/account/{client:name}-{date}',
		self::DAILY_REPORT => 'compta/daily/{date}',
		self::EXTRACTION => 'compta/extraction/{date}',
		self::BACKUP => '{name}-{datetime}.gz',
		self::BACKUP_MEDIA => 'media-{datetime}.taz',
		self::BACKUP1 => '{name}.gz',
		self::BACKUP_MEDIA1 => 'media.taz',
		self::DOCUMENT => 'document/document/{client:name}/{model:name}',
		self::PICTURES => '{id}/{name}',
		self::FRAME_ORDERS => 'document/frames/{client:name}-{date}',
	];
	
	/** Directories */
	protected static $DIRS = [
		self::LATE_BILLS => 'document/late-bills',
		self::ACCOUNT => 'document/account',
		self::DAILY_REPORT => 'daily',
		self::PICTURES => 'pictures',
		self::EXTRACTION => 'extraction',
		self::BACKUP => 'backup',
		self::DOCUMENT => 'document',
		self::FRAME_ORDERS => 'document/frames',
	];
	
	/*	Checks if directory exists, if not creates it.
	 *
	 *	@param string $dirname Directory name.
	 *
	 *	@return string|null	Directory name if exists and is writable, null otherwise.
	 */
	private function checkDir($dirname) {
		$dirpath = self::getDocumentRoot().$dirname;
		if(!is_dir($dirpath))
		    if(!mkdir($dirpath, 0777, true))
				return false;
		return $dirname;
	}	

	/**
	 * Adds .pdf if not present
	 */
	private function checkPDF($filename) {
		return (strtolower(substr($filename, -4, 4)) != '.pdf') ? $filename.'.pdf' : $filename;
	}	

	/**
	 * Get storage root
	 */
	public static function getFileStoreUrl($full = false) {
		return Url::to(['/documents/'], $full);
	}	

	/**
	 * Get storage root
	 */
	public static function getFileStoreDirectory() {
		return Yii::getAlias('@app').DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR;
	}	

	/**
	 * Get file root
	 */
	private static function getFileRoot($what) {
		return self::getFileStoreDirectory().$what.DIRECTORY_SEPARATOR;
	}	

	/**
	 * Get document root
	 */
	public static function getDocumentRoot() {
		return self::getFileRoot(self::FILESTORE_DOCUMENTS);
	}	

	/**
	 * Get picture root
	 */
	public static function getPictureRoot() {
		return self::getFileRoot(self::FILESTORE_PICTURES);
	}	

	/**
	 * Get backup root
	 */
	public static function getBackupRoot() {
		$backup_dir = Yii::getAlias('@runtime') . '/' . self::FILESTORE_BACKUPS . '/';
		if(!is_dir($backup_dir))
			mkdir($backup_dir);
		return $backup_dir;
	}	

	/*	Creates file name of requested purpose.
	 *
	 *	@param string $for Purpose of file.
	 *	@param string $name String appended to filename; may contain extension.
	 *	@param string $client app\models\Client Client whose name may be needed.
	 *	@param string $client app\models\Document Document whose "name" may be needed.
	 *
	 *	@return string|null	File name (full) if parent directory exists and is writable, null otherwise.
	 */
	public function getFilename($for, $name, $model = null, $client = null) {
		Yii::trace('for='.$for, 'RuntimeDirectoryManager::getFilename');
		if($for == self::PICTURES)
			return $model ? Yii::$app->params['picturePath'].DIRECTORY_SEPARATOR.$model->id.DIRECTORY_SEPARATOR.$name : null;

		$template = self::$TEMPLATE[$for];
		$template = str_replace('{date}', date('Y-m-d'), $template);
		$template = str_replace('{datetime}', date('Y-m-d-H-i-s'), $template);
		$template = str_replace('{for}', $for, $template);
		if($model)
			$template = str_replace('{model:name}', $model->name, $template);			
		if(isset($model->client))
			$template = str_replace('{client:name}', $model->client->sanitizeName(), $template);
		else if($client) // client is supplied, but not model. Example: A cover letter.
			$template = str_replace('{client:name}', $client->sanitizeName(), $template);

		$template = str_replace('{name}', $name, $template);
		
		if(in_array($for,[self::BACKUP, self::BACKUP1, self::BACKUP_MEDIA, self::BACKUP_MEDIA1]))
			$filename = $template; // @web not defined in web\application
		else
			$filename = self::checkPDF($template); // @web not defined in web\application
		
		Yii::trace('filename='.$filename, 'RuntimeDirectoryManager::getFilename');
		
		return self::checkDir(dirname($filename)) ? $filename : null;
	}



	/*	Checks if directory exists to store files of requested purpose, if not creates it.
	 *
	 *	@param string $for Purpose of directory.
	 *
	 *	@return string|null	Directory name if exists and is writable, null otherwise.
	 */
	public function getDirectory($for) {
		if($for == self::PICTURES)
			$dirname = self::getPictureRoot();
		else
			$dirname = self::getDocumentRoot().self::$DIRS[$for].DIRECTORY_SEPARATOR;

		return self::checkDir($dirname) ? $dirname : null;
	}
	
	

}