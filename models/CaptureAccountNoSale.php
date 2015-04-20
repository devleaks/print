<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class CaptureAccountNoSale extends Model
{
	public $amount;
	public $method;
	public $date;
	public $client_id;
	public $note;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'client_id'], 'required'],
            [['amount', 'method', 'date', 'client_id', 'note'], 'safe'],
			[['amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
			[['client_id'], 'integer'],
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
            'amount' => Yii::t('store', 'Amount'),
            'method' => Yii::t('store', 'Payment Method'),
            'note' => Yii::t('store', 'Note'),
            'date' => Yii::t('store', 'Date'),
            'client_id' => Yii::t('store', 'Client'),
        ];
    }
}
