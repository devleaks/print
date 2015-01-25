<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Refund extends Document
{
	const TYPE = 'REFUND';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_REFUND, 'Credit::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_REFUND]);
    }


	/**
	 * Returns amount due.
	 *
	 * @return number Amount due.
	 */
	public function isPaid() {
		return $this->getBalance() > -Document::PAYMENT_LIMIT;
	}


    /**
     * @inheritdoc
     */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$actions = [];
		switch($this->status) {
			case $this::STATUS_OPEN:
				$actions[] = '{refund}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = '{label:closed}';
				break;
		}
		return implode(' ', $actions) . ' ' . parent::getActions();
	}

}