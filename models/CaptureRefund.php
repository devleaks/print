<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture refund parameters.
 */
class CaptureRefund extends Model
{
	public $method;
	public $date;
	public $note;
	public $selection;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method', 'date', 'note'], 'safe'],
            [['method'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 160],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'method' => Yii::t('store', 'Payment Method'),
            'note' => Yii::t('store', 'Note'),
            'date' => Yii::t('store', 'Date'),
        ];
    }
}
