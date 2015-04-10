<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture filename for upload.
 */
class CaptureUpload extends Model
{
	public $filename;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename'], 'file'],
            [['filename'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filename' => Yii::t('store', 'Filename'),
        ];
    }
}
