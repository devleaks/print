<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class to hold master cut left overs.
 */
class Cash extends _Cash
{
	public $amount_virgule;
	public $mode;
	const CREDIT = 'ACREDIT';
	const DEBIT  = 'ADEBIT';

    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
                'userstamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                        ],
                        'value' => function() { return isset(Yii::$app->user) ? Yii::$app->user->id : null; },
                ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for pattern masking
			[['amount_virgule'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
			[['amount_virgule', 'mode'], 'safe']
		]);
	}


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'amount_virgule' => Yii::t('store', 'Amount'),
		]);
	}

	public function getBalance($last = null) {
		$q = self::find();
		if($last)
			$q->where(['<=','created_at',$last]);
		return round($q->sum('amount'), 2);
	}


	public function delete() {
		if($account = $this->getAccounts()->one())
			$account->delete();
		parent::delete();
	}
	
	/**
	 * What was Cash money used for?
	 *
	 * @return string
	 */
	public function whatFor() {
		$str = '';
		if($account = Account::findOne(['cash_id' => $this->id])) {
			$str = '';
			foreach($account->getPayments()->each() as $payment) {
				if($doc = Document::find()->andWhere(['sale' => $payment->sale])->orderBy('created_at desc')->one()) {
					$str .= $doc->name.',';
				}
			}
		}
		return rtrim($str,',');
	}
	

}
