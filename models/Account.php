<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 *	Gii Extension class
 */
class Account extends _Account
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
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

	public function deleteWithCash() {
		if($cash = $this->getCash()->one())
			$cash->delete(); //$cash will delete it.
		else
			$this->delete();
	}
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['sale' => 'sale'])->viaTable('payment', ['account_id' => 'id']);
    }
    
	/**
	 * What was Account money used for?
	 *
	 * @return string
	 */
	public function whatFor() {
		$str = '';
		foreach($this->getPayments()->each() as $payment) {
			if($doc = Document::find()->andWhere(['sale' => $payment->sale])->orderBy('created_at desc')->one()) {
				$str .= $doc->name.',';
			}
		}
		return rtrim($str,',');
	}
}