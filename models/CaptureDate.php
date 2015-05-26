<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture searchess.
 */
class CaptureDate extends Model
{
	public $date;
	public $action;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'action'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => Yii::t('store', 'Date'),
        	'action' => Yii::t('store', 'Action'),
        ];
    }
}
