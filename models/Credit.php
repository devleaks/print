<?php

namespace app\models;

use Yii;

class Credit extends Document
{
	const TYPE = 'REFUND';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_CREDIT, 'Credit::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_CREDIT]);
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
		return implode(' ', $actions) . ' {view} {print}';//cannot get parent:: which is Order
	}

}