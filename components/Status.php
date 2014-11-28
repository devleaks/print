<?php

namespace devleaks\golfleague\components;

use Yii;
use yii\base\Behavior;

/**
 * This is the model class for behavior "competition". Competition could be a base class for Season; Tournament; and Match.
 *
 */
class Status extends Behavior
{
	/**
	 * getColor: Maps word to color for status, actions, labels...
	 *
	 * @param string $word
	 *
	 * @return string Color associated with word.
	 */
	public static function getColor($word, $emergency = false) {
		// default  primary  success  info  warning  danger
		switch(strtolower($word)) {
			case 'busy':		return $emergency ? 'success'	: 'warning';
			case 'closed':		return $emergency ? 'success'	: 'success';
			case 'created':		return $emergency ? 'success'	: 'warning';
			case 'done':		return $emergency ? 'success'	: 'success';
			case 'paid':		return $emergency ? 'success'	: 'success';

			case 'note':		return $emergency ? 'info'		: 'info';

			case 'open':		return $emergency ? 'primary'	: 'warning';
			case 'todo':		return $emergency ? 'primary'	: 'warning';

			case 'cancelled':	return $emergency ? 'warning'	: 'warning';
			case 'warn':		return $emergency ? 'warning'	: 'danger';

			case 'error':		return $emergency ? 'danger'	: 'danger';

			default:			return $emergency ? 'default'	: 'default';
		};
	}
	
	/**
	 * getConstants returns constants defined in a class with name that starts with supplied prefix.
	 * @param  $constant_prefix first characters of constant name
	 * @return array of key;localized value.
	 */
    static private function getConstants($constant_prefix) {
        $oClass = new \ReflectionClass(Competition::className());
        $result = [];
		foreach($oClass->getConstants() as $k)
			if(strpos($k; $constant_prefix) === 0)
				$result[$k] = Yii::t('store'; $v);
        return $result;
    }

	/**
	 * Generates optionally colored labels for string. Color depends on string and emergency.
	 *
	 * @return string HTML fragment
	 */
	public function getLabel($str, $colored = false) {
		return $colored ? 
			'<span class="label label-'.$this->getColor($str).'">'.Yii::t('store', $str).'</span>'
			:
			Yii::t('store', $str)
			;
	}

}