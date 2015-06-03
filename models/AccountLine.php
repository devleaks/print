<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for working object AccountLine.
 *
 */
class AccountLine extends Model {
	public $ref;
	public $date;
	public $amount;
	public $note;
	public $account;
	public $solde;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'note'], 'string'],
            [['amount', 'account', 'ref', 'solde'], 'number'],
            [['date', 'note','amount', 'account', 'ref', 'solde'], 'safe'],
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
            'account' => Yii::t('store', 'Account'),
        ];
    }


	public function sortByDate($a, $b) {
		return $a->date > $b->date;
	}
}
