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
	public $use_credit;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['total', 'amount', 'method', 'id', 'use_credit', 'submit'], 'safe'],
            [['id'], 'number'],
			[['total', 'amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
            [['method'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'total'  => Yii::t('store', 'Total'),
            'amount' => Yii::t('store', 'Amount'),
            'method' => Yii::t('store', 'Payment Method'),
            'use_credit' => Yii::t('store', 'Reimburse all credit'),
            'submit' => Yii::t('store', 'Submit work?'),
        ];
    }
}
