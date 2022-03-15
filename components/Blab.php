<?php

namespace app\components;

use Yii;

/**
 *	Constant behavior/trait to print strings.
 */
trait Blab {
	public $_blab;

	protected function blabInit() {
		if(!isset($this->_blab))
			$this->_blab = [];
	}
	
	protected function blabReset() {
		$this->_blab = [];
	}
	
	protected function blab($str) {
		$this->blabInit();
		$this->_blab[] = $str;
	}
	
	protected function blabOut() {
		$this->blabInit();
		$str = implode('<br/>', $this->_blab);
		$this->blabReset();
		return $str;
	}

	protected function asDateTime($date) {
		Yii::$app->formatter->datetimeFormat = 'php:D j M Y G:i';
		return str_replace(':', 'h', Yii::$app->formatter->asDateTime(new \DateTime($date)));
	}
	
}