<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class CapturePayment extends Model
{
	public $id;
	public $total;
	public $amount;
	public $method;
	public $submit;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['total', 'amount', 'method', 'id', 'submit'], 'safe'],
            [['total', 'amount', 'id'], 'number'],
            [['method'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'total' => Yii::t('store', 'Total'),
            'amount' => Yii::t('store', 'Amount'),
            'method' => Yii::t('store', 'Payment Method'),
            'save' => Yii::t('store', 'Submit Work?'),
        ];
    }
}
