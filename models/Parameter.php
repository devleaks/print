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
class Parameter extends _Parameter
{
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


	public static function getName($domain, $text) {
		$p = self::find()->where(['domain' => $domain, 'value_text' => $text])->one();
		return $p ? $p->name : '';
	}

	public static function getTextValue($domain, $name, $default = '') {
		$p = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		return $p ? $p->value_text : $default;
	}

	public static function getMLText($domain, $name, $lang = null) {
		$language = $lang ? $lang : Yii::$app->language;
		$p = self::find()->where(['domain' => $domain, 'name' => $name, 'lang' => $lang])->one();

		$p_fr  = self::find()->where(['domain' => $domain, 'name' => $name, 'lang' => 'fr'])->one();
		$p_any = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		
		return $p ? $p->value_text : ($p_any ? $p_any->value_text : $domain.'::'.$name.'('.$lang.')');
	}

	public static function getIntegerValue($domain, $name, $default = null) {
		$p = self::find()->where(['domain' => $domain, 'name' => $name])->one();
		return $p ? $p->value_int : $default;
	}
}
