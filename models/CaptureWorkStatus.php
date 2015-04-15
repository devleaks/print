<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture work line status.
 */
class CaptureWorkStatus extends Model
{
	public $status;
	public $keylist;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'keylist'], 'required'],
            [['status', 'keylist'], 'safe'],
			['status', 'in', 'range' => array_keys(Work::getStatuses())]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status' => Yii::t('store', 'Status'),
            'keylist' => Yii::t('store', 'List of IDs'),
        ];
    }
}
