<?php

namespace app\models;

use Yii;

class Ticket extends Order
{
	const TYPE = 'TICKET';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_TICKET, 'Ticket::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_TICKET]);
    }

	/**
	 * @inheritdoc
	 */
	protected function statusUpdated() {
		Yii::trace('status='.$this->status, 'Ticket::statusUpdated()');
		if($this->status == self::STATUS_DONE) {
			$this->updatePaymentStatus();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function updatePaymentStatus() {
		Yii::trace('isPaid='.$this->isPaid(), 'Ticket::updatePaymentStatus');
		if(!$this->isBusy())
			$this->setStatus($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY);
		// otherwise, we leave the status as it is
	}

    /**
     * @inheritdoc
	 */
	public function getActions($show_work = false) {
		$actions = [];

		$ret = '';
		$work = $this->getWorks()->one();
		if( $show_work && $work ) $ret .= '<p>'.$work->getTaskIcons(true, true, true).'</p>';

		switch($this->status) {
			case $this::STATUS_CREATED:
				$actions[] = '{edit}';
				$actions[] = '{cancel}';
				break;
			case $this::STATUS_OPEN:
				$actions[] = '{edit}';
				$actions[] = '{submit}';
				$actions[] = '{cancel}';
				break;
			case $this::STATUS_WARN:
				$actions[] = '{warn}';
			case $this::STATUS_TODO:
			case $this::STATUS_BUSY:
				$actions[] = '{cancel}';
				if( $work  ) { // there should always be a work if doc status is TODO or BUSY or WARN
					$actions[] = '{work}';
					$actions[] = '{workterminate}';
				} else
					$actions[] = '{terminate}';
				break;
			case $this::STATUS_DONE:
			case $this::STATUS_TOPAY:
			case $this::STATUS_SOLDE:
				$actions[] = '{receive}';
				break;
			case $this::STATUS_CANCELLED:
				$actions[] = '{label:cancelled}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = '{label:closed}';
				break;
		}
		return $ret . implode(' ', $actions) . ' {view} {print}';//cannot get parent:: which is Order
	}

}