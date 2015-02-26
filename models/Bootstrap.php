<?php

namespace app\models;

use Yii;
/**
 * This is the model class for new segments.
 */
class Bootstrap {
	// default  primary  success  info  warning  danger
	
	public static function getColors() {
		return [
			'default' => '#eeeeee',
			'primary' => '#337ab7',
			'success' => '#5cb85c',
			'info' => '#5bc0de',
			'warning' => '#f0ad4e',
			'danger' => '#d9534f',
		];
	}
	
	public static function getBackgroundColors() {
		return [
			'default' => '#eeeeee',
			'primary' => '#337ab7',
			'success' => '#5cb85c',
			'info' => '#d9edf7',
			'warning' => '#fcf8e3',
			'danger' => '#f2dede',
		];
	}
	
	public static function getTextColors() {
		return [
			'default' => '#000000',
			'primary' => '#eeeeee',
			'success' => '#3c763d',
			'info' => '#31708f',
			'warning' => '#8a6d3b',
			'danger' => '#a94442',
		];
	}
	
}