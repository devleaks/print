<?php

namespace app\models;

use Yii;

class Bid extends Document
{
	const TYPE = 'BID';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_BID, 'Bid::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_BID]);
    }


    /**
     * @inheritdoc
	 */
	public function convert($ticket = false) { // convert BID to ORDER
		if( $existing_next = $this->getOrder() )
			return $existing_next;

		$copy = $this->deepCopy( $ticket ? self::TYPE_TICKET : self::TYPE_ORDER );
		$copy->parent_id = $this->id;
		$copy->status = self::STATUS_OPEN;
		$copy->save();

		if(Parameter::isTrue('application', 'auto_submit_work')) {
			Yii::trace('auto_submit_work for '.$copy->id, 'Document::convert');
			$work = $copy->createWork();
		}

		$this->status = self::STATUS_CLOSED;
		$this->save();	

		return $copy;
	}
	
	
	public function getOrder() {
		$o = Order::find()->andWhere(['parent_id' => $this->id])->one();
		if(!$o)
			$o = Ticket::find()->andWhere(['parent_id' => $this->id])->one();
		return $o;
	}


    /**
     * @inheritdoc
	 */
	public function getActions($show_work = false) {
		$actions = [];
		$actions[] = '{copy}';
		switch($this->status) {
			case $this::STATUS_TOPAY:
			case $this::STATUS_OPEN:
				$actions[] = '{edit}';
				$actions[] = '{convert}';
				$actions[] = '{cancel}';
				break;
			case $this::STATUS_CLOSED:
				if( $order = $this->getDocuments()->where(['document_type' => Order::TYPE_ORDER])->one() ) {
					$actions[] = '{link:ordered}';
				} else {
					$actions[] = '{convert}';
					$actions[] = '{label:closed}';
				}
				break;
			case $this::STATUS_CANCELLED:
				$actions[] = '{label:cancelled}';
				break;
		}
		return implode(' ', $actions) . ' ' . parent::getActions();
	}
}