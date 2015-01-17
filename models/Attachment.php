<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to temporary keep email attachments.
 */
class Attachment extends Model
{
	/** */
	public $filename;
	/** */
	public $title;
	/** */
	public $mimetype;
}
