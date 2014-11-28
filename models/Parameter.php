<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "parameter".
 *
 * @property string $domain
 * @property string $name
 * @property string $value_text
 * @property double $value_number
 * @property integer $value_int
 * @property string $value_date
 */
class Parameter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'name'], 'required'],
            [['value_number'], 'number'],
            [['value_int'], 'integer'],
            [['value_date'], 'safe'],
            [['domain'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 40],
            [['value_text'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('store', 'Domain'),
            'name' => Yii::t('store', 'Name'),
            'value_text' => Yii::t('store', 'Value Text'),
            'value_number' => Yii::t('store', 'Value Number'),
            'value_int' => Yii::t('store', 'Value Int'),
            'value_date' => Yii::t('store', 'Value Date'),
        ];
    }

	/**
	 * Builds a (id,name) pairs for paramter out of a parameter domain
	 *
	 * @param string $domain Parameter domain
	 *
	 * @param string $what Parameter value to return
	 *
	 * @param string $order_by Parameter value to order by
	 *
	 * @param Array() (id,name) pairs
	 */
	public static function getSelectList($domain, $what, $order_by = null) {
		return ArrayHelper::map(self::find()->where(['domain'=>$domain])->orderBy($order_by ? $order_by : $what)->asArray()->all(), 'name', $what);
	}
	
	/**
	 * Returns list of currently used parameter domain names.
	 *
	 * @return Array() name,value pairs with domain names.
	 */
	public static function getDomains() {
		return ArrayHelper::map(self::find()->orderBy('domain')->distinct()->asArray()->all(), 'domain', 'domain');
	}


	/**
	 * Returns list of currently used parameter domain names.
	 *
	 * @param string $domain  Domain name.
	 * @param string $name  Parameter name.
	 *
	 * @return Whether parameter name in domain name is set and integer value is true (i.e. not null or zero).
	 */
	public static function isTrue($domain, $name) {
		$p = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		return $p ? $p->value_int : false;
	}


	public static function getTextValue($domain, $name, $default = '') {
		$p = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		return $p ? $p->value_text : $default;
	}

	public static function getIntegerValue($domain, $name, $default = null) {
		$p = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		return $p ? $p->value_int : $default;
	}
}
