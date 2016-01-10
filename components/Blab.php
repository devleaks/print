<?php

namespace app\components;

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
		$str = implode($this->_blab, '<br/>');
		$this->blabReset();
		return $str;
	}

}