<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "extraction".
 *
 */
class CreditLine extends Model {
	public $ref;
	public $date;
	public $amount;
	public $note;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'note'], 'string'],
            [['amount', 'account', 'ref'], 'number'],
            [['date', 'note','amount', 'ref'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note' => Yii::t('store', 'Note'),
            'date' => Yii::t('store', 'Date'),
            'amount' => Yii::t('store', 'Amount'),
        ];
    }

}
