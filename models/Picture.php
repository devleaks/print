<?php

namespace app\models;

use app\components\RuntimeDirectoryManager;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "picture".
 *
 * @property integer $id
 * @property string $name
 * @property integer $document_line_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $mimetype
 * @property string $filename
 *
 * @property DocumentLine $orderLine
 */
class Picture extends _Picture
{
	/** sub directory in @web containing pictures */
	const PATH = 'pictures';

    /**
     * Maximum size of images associated with document
     * @var integer
     */
    const maxsize   = 400; // px;

    /**
     * Maximum size of thumbnail images associated with document
     * @var integer
     */
    const thumbsize = 150; // px;

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
	 * Returns URL to picture file.
	 *
	 * @return string Path to picture file.
	 */
    public function getUrl()
    {
        return Yii::getAlias('@web').'/pictures/'.$this->filename;
    }

	/**
	 * Returns URL to thumbnail file.
	 *
	 * @return string Path to thumbnail file.
	 */
    public function getThumbnailUrl()
    {
		$thumbPath = $this->getThumbpath();
		Yii::trace($thumbPath, 'Picture::getThumbnailUrl');
		if(file_exists($thumbPath)) {
	        $url = $this->getUrl();
	        $pos = strrpos($url, '.');
	        return ($pos !== false) ? substr_replace($url, '_t', $pos, strlen($url)) . substr($url, $pos) : $url;
		} else {
			return Yii::getAlias('@web').'/assets/i/thumbnail.png';
		}
    }

	/**
	 * Returns thumbnail name based on file name.
	 *
	 * @return string Thumbnail file name.
	 */
    public function getThumbnailName()
    {
        $thn = $this->filename;
        $pos = strrpos($thn, '.');
        return ($pos !== false) ? substr_replace($thn, '_t', $pos, strlen($thn)) . substr($thn, $pos) : $thn;
    }

	/**
	 * Delete file, thumbnail (if any) and commit suicide.
	 *
	 */
	public function deleteCascade() {
		$tn = RuntimeDirectoryManager::getPictureRoot() . $this->getThumbnailName();
		if(file_exists($tn))
			unlink($tn);

		$fn = RuntimeDirectoryManager::getPictureRoot() . $this->filename;
		if(file_exists($fn))
			unlink($fn);
		$dir = dirname($fn);
		if($this->is_dir_empty($dir))
			rmdir($dir);

		$this->delete();
	}

	/**
	 * Utility function to duplicate a file system folder.
	 *
	 * @param string src Source folder path
	 * @param string dst Destination folder path
	 */
	private function recurse_copy($src,$dst) {
	    if(is_dir($src)) {		
			$dir = opendir($src);
		    @mkdir($dst);
		    while(false !== ($file = readdir($dir)) )
		        if (($file != '.') && ($file != '..')) {
		            if (is_dir($src . DIRECTORY_SEPARATOR . $file))
		                recurse_copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
		            else
		                copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
		        }
		    closedir($dir);
		}
	}

	/**
	 * Check whether directory is empty (and can be deleted).
	 *
	 * @param string $dir Directory path
	 * @return boolean Directory is empty.	
	 */
	function is_dir_empty($dir) {
		// return !(new \FilesystemIterator($dir))->valid();
		if (!is_readable($dir)) return null; 
		return (count(scandir($dir)) == 2); // . et ..?
	}

	/**
	 * Deep copy Picture model for a supplied documentline. Copies both image and thumbnail.
	 *
	 * @param integer document_line_id Destination DocumentLine identifier
	 */
	public function deepCopy($document_line_id) {
		// create a copy Picture object
		$copy = new Picture($this->attributes);
		$copy->id = null;
		$copy->document_line_id = $document_line_id;
		// get old order line   BASE/OL/FN.EXT
		$fs = strpos($copy->filename, DIRECTORY_SEPARATOR);
		$ss = strpos($copy->filename, DIRECTORY_SEPARATOR, $fs + 1);
		$old = substr($copy->filename, $fs, $ss - $fs + 1); // /123/
 		//Yii::trace('Fn: '.$copy->filename.', fs='.$fs.', ss='.$ss.', old='.$old, 'Picture::deepCopy');
		$copy->filename = str_replace($old, DIRECTORY_SEPARATOR.$document_line_id.DIRECTORY_SEPARATOR, $copy->filename);
 		//Yii::trace('Fw: '.$copy->filename, 'Picture::deepCopy');
		
		// now copies files	(duplicate dir with images)	
		$orig = RuntimeDirectoryManager::getPictureRoot() . $this->filename;
		$ls = strrpos($orig ,DIRECTORY_SEPARATOR);
		$origdir = substr($orig, 0, $ls);
 		//Yii::trace('Od: '.$origdir.', ls='.$ls, 'Picture::deepCopy');
		$dest = RuntimeDirectoryManager::getPictureRoot() . $copy->filename;
		$ls = strrpos($dest ,DIRECTORY_SEPARATOR);
		$destdir = substr($dest, 0, $ls);
 		//Yii::trace('Dd: '.$destdir.', ls='.$ls, 'Picture::deepCopy');
		$this->recurse_copy($origdir, $destdir);

		$copy->save();
		return $copy;
	}
	
	/**
	 * To be done after saving the image for the first time: Generate thumbnail image and resize "full size" image.
	 */
	public function generateThumbnail() {
		$imagePath = $this->getFilepath();
		$pic = Yii::$app->image->load($imagePath);
		$thumbPath = $this->getThumbpath();
		Yii::trace('Image:'.$pic->width.' X '.$pic->height.'.', 'DocumentLineController::loadImages');
		if($pic->width > self::thumbsize || $pic->height > self::thumbsize) {
			$ratio = ($pic->width > $pic->height) ? $pic->width / self::thumbsize : $pic->height / self::thumbsize;
			$newidth  = round($pic->width  / $ratio);
			$neheight = round($pic->height / $ratio);
			$pic->resize($newidth, $neheight);
			$pic->flip(\yii\image\drivers\Image::HORIZONTAL);
			Yii::trace('Image:'.$pic->width.' X '.$pic->height.' for thumbnail.', 'DocumentLineController::loadImages');
			$pic->save($thumbPath);
			Yii::trace('Image:'.$pic->width.' X '.$pic->height.' for thumbnail ('.$thumbPath.').', 'DocumentLineController::loadImages');
		} else { // else picture already smaller than thumbnail, so we leave it as it is
			$pic->save($thumbPath);
		}
		if($pic->width > self::maxsize || $pic->height > self::maxsize) {
		    $ratio = ($pic->width > $pic->height) ? $pic->width / self::maxsize : $pic->height / self::maxsize;
		    $newidth  = $pic->width  / $ratio;
		    $neheight = $pic->height / $ratio;
		    $pic->resize($newidth, $neheight);
			Yii::trace('Image:'.$pic->width.' X '.$pic->height.' after resize.', 'DocumentLineController::loadImages');
		    $pic->save();
		}
	}

	/**
	 * Get full path to thumbnail image.
	 *
	 * @return string Full path to thumbnail image
	 */
	public function getThumbpath() {
		return RuntimeDirectoryManager::getPictureRoot() . $this->getThumbnailName();
	}

	
	/**
	 * Get full path to image file.
	 *
	 * @return string Full path to image file
	 */
	public function getFilepath() {
		return RuntimeDirectoryManager::getPictureRoot() . $this->filename;
	}

}
