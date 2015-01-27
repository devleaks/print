<?php

namespace app\models;

use Yii;
use app\models\Sequence;

/**
 * This is the model class for table "order".
 */
class Order extends Document
{
    /**
     * @inheritdoc
	 */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_ORDER, 'Order::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_ORDER]);
    }

	
    /**
 	 * Note: modified to have the global rebate line always listed LAST
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLines() {
		$item = Item::findOne(['reference' => Item::TYPE_REBATE]);
		$special_line_query = DocumentLine::find()
							->andWhere(['document_id' => $this->id])
							->andWhere(['item_id' => $item->id]);
							
		$query = DocumentLine::find()
							->andWhere(['document_id' => $this->id])
							->andWhere(['!=', 'item_id', $item->id])
							->union($special_line_query);

        return $query;
    }


    /**
     * @inheritdoc
	 */
	public function convert($ticket = false) { // convert ORDER into BILL
     	/** if a following document already exists, it returns it rather than create a new one. */
		if( $existing_next = $this->find()->andWhere(['parent_id' => $this->id])->andWhere(['document_type' => self::TYPE_BILL])->one() )
			return $existing_next;

		$copy = $this->deepCopy(self::TYPE_BILL);
		$copy->parent_id = $this->id;	
		$copy->name = substr($this->due_date,0,4).'-'.Sequence::nextval('bill_number'); // get a new official bill number; $this->due_date or $copy->due_date?
		Yii::trace($this->isPaid()?'Oui':'Non', 'Order::convert');
		$copy->status = ($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_SOLDE);
		$copy->save();
		
		if(Parameter::isTrue('application', 'auto_send_bill')) {
			Yii::trace('auto_send_bill for '.$copy->id, 'Document::convert');
			$copy->send();
		}

		$this->status = self::STATUS_CLOSED;
		$this->save();	

		return $copy;
	}


	/**
	 * @inheritdoc
	 */
	protected function statusUpdated() {
		Yii::trace('up', 'Order::statusUpdated()');
		if($this->status == self::STATUS_CANCELLED) {
			if($work = $this->getWorks()->one()) {
				foreach($work->getWorkLines()->each() as $wl) {
					$wl->status = Work::STATUS_CANCELLED;
					$wl->save();
				}
				$work->status = Work::STATUS_CANCELLED;
				$work->save();
			}
		}
		
		if($this->status == self::STATUS_DONE)
			$this->completed();
	}


	public function getBill() {
		return ($this->bom_bool and $this->parent_id != null) ?
			Bill::findDocument($this->parent_id)
			:
			Bill::findOne(['parent_id' => $this->id]);
	}
	/**
	 * @inheritdoc
	 */
	public function updatePaymentStatus() {
		Yii::trace('up', 'Order::updatePaymentStatus');
		if($bill = $this->getBill())
			$bill->updatePaymentStatus();
		else {// regular order
			if(!$this->isBusy())
				$this->setStatus($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_SOLDE);
		}
	}

    /**
     * If order is completed, send email to client, and create bill if parameters allow it.
	 */
	private function completed() {
		Yii::trace('Order::completed');
		// 1. notify client of completion
		if(Parameter::isTrue('application', 'auto_notify_completion')) {
			if($this->client->email != '') {
				Yii::trace('auto_notify_completion '.$this->id, 'Order::completed');
				$lang_before = Yii::$app->language;
				Yii::$app->language = $this->client->lang ? $this->client->lang : 'fr';
				try {
					Yii::$app->mailer->compose('order-completed', ['model' => $this])
					    ->setFrom( Yii::$app->params['fromEmail'] )
					    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $this->client->email )
					    ->setSubject(Yii::t('store', $this->document_type).' '.$this->name)
					    ->send();
					Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
				} catch (Swift_TransportException $STe) {
					Yii::error($STe->getMessage(), 'CoverLetter::send::ste');
					Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
				} catch (Exception $e) {
					Yii::error($e->getMessage(), 'CoverLetter::send::e');				
					Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
				}
				Yii::$app->language = $lang_before;
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has no email address. No notification mail sent.'));
			}
		}
		// 2. Create bill from order
		if(!$this->bom_bool && Parameter::isTrue('application', 'auto_create_bill')) { // create bill since order is completed
			Yii::trace('auto_create_bill '.$this->id, 'Order::completed');
			$bill = $this->convert();
		}
	}
	
    /**
	 * createWork create work to complete the order, loops and create tasks for each order line
	 *
     * @param $defaultWork boolean Whether to create a default work line (with Task of type Control Task, with ID=0)
	 *                             for each order line if no work line was added. Default to false.
	 *
     * @return app\models\Work
     */
	public function createWork($defaultWork = false) {
		if( $existing_work = $this->getWorks()->one() )
			return $existing_work;

		$this->numberLines();
		
		$work = new Work();
		$work->document_id = $this->id;
		$work->due_date = $this->due_date;
		$work->priority = $this->priority;
		$work->status = Work::STATUS_TODO;
		$work->save(); // to generate id
		
		foreach($this->getDocumentLines()->each() as $ol)
			$ol->createTask($work, $defaultWork);

		// if work to do, set order status to do, otherwise, it is done.
		if ($work->getWorkLines()->count() == 0) {
			$work->delete();
			$work = null;
		}
		$this->setStatus(Order::STATUS_TODO);
		// $this->save(); // saved in statusUpdated()
		return $work;
	}
	
	
    /**
     * @inheritdoc
	 * 
	 * Icons:
	 *
	 * create:		plus
	 * submit:		tasks
	 * detail:		eye-open
	 * terminate:	ok-circle
	 * convert:		send
	 * cancel:		remove
	 * close:		off
	 * print:		print
	 * label:		qrcode or tag
	 * send:		envelope
	 * work:		tasks
	 */
	public function getActions($show_work = false) {
		$actions = [];

		$ret = '';
		$work = $this->getWorks()->one();
		if( $show_work && $work ) $ret .= '<p>'.$work->getTaskIcons(true, true, true).'</p>';

		switch($this->status) {
			case $this::STATUS_CREATED:
				$actions[] = '{edit}';
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
			case $this::STATUS_SOLDE:
			case $this::STATUS_TOPAY:
			case $this::STATUS_DONE:
				$actions[] = '{bill}';
				break;
			case $this::STATUS_CANCELLED:
				$actions[] = '{label:cancelled}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = ($this->getBill() ? '{link:billed}' : '{bill}');
				break;
		}
		return $ret . implode(' ', $actions) . ' ' . parent::getActions();
	}
}

