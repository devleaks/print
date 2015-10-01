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
	public $click;
	public $total;
	public $amount;
	public $method;
	public $submit;
	public $note;
	public $use_credit;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['total', 'amount', 'method', 'id', 'use_credit', 'note', 'submit', 'click'], 'safe'],
            [['id'], 'number'],
			[['total', 'amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
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
            'total'  => Yii::t('store', 'Total to Pay'),
            'amount' => Yii::t('store', 'Amount Paid'),
            'method' => Yii::t('store', 'Payment Method'),
            'use_credit' => Yii::t('store', 'Reimburse all credit'),
            'note' => Yii::t('store', 'Note'),
            'submit' => Yii::t('store', 'Submit work?'),
        ];
    }
}
