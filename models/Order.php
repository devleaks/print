<?php

namespace app\models;

use Yii;
use app\models\Sequence;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "order".
 */
class Order extends Document
{
	const DEFAULT_MINIMUM_DAYS = 2;
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
		if( $existing_next = $this->getBill() )
			return $existing_next;

		$copy = $this->deepCopy(self::TYPE_BILL);
		$copy->parent_id = $this->id;	
		$copy->name = Bill::getNextBillNumber();
		$copy->updatePrice();
		$copy->setStatus(self::STATUS_TOPAY);
		$copy->save();
		
		if(Parameter::isTrue('application', 'auto_send_bill')) {
			Yii::trace('auto_send_bill for '.$copy->id, 'Document::convert');
			$copy->send();
		}

		//$this->status = self::STATUS_CLOSED;
		//$this->save();	

		return $copy;
	}


	/**
	 * @inheritdoc
	 */
	protected function statusUpdated() {
		Yii::trace('status='.$this->status, 'Order::statusUpdated()');
		switch($this->status) {
			case self::STATUS_CANCELLED:
				if($work = $this->getWorks()->one()) {
					foreach($work->getWorkLines()->each() as $wl) {
						$wl->status = Work::STATUS_CANCELLED;
						$wl->save();
					}
					$work->status = Work::STATUS_CANCELLED;
					$work->save();
				}
				break;
			case self::STATUS_DONE:
				$this->completed();
				break;
		}		
	}


	/**
	 * @inheritdoc
	 */
	public function getBill() {
		return $this->bom_bool ? Bill::findOne($this->bill_id) : Bill::findOne(['parent_id' => $this->id]);
	}


	protected function updatePaymentStatus() {
		if($bill = $this->getBill()) // If the order has already a bill, the bill contains the payment status
			return $bill->updatePaymentStatus();
		else { //if(!$this->isBusy() && ($this->status != self::STATUS_NOTIFY)) { // order with no bill
			return $this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY;
		} // otherwise, we leave the status as it is
		return $this->status;
	}


    /**
     * Checks whether due_date is either past, or not too far in future.
	 */
	public function closeToDueDate() {
		$closeToDueDate = true;
		$now = date('Y-m-d H:i:s');
		if($this->due_date > $now) {
			if($date_limit = Parameter::getIntegerValue('application', 'min_days', self::DEFAULT_MINIMUM_DAYS)) {
				$diff = strtotime($this->due_date) - time(); // in secs.
				$diff = ceil($diff / (24 * 60 * 60));
				$closeToDueDate = ($diff <= $date_limit); // If we are finished too early, we do NOT notify the person right away.
				Yii::trace('Min days='.$date_limit.', diff='.$diff.': '.($closeToDueDate ? 'send it' : 'DO NOT send it'));
			}
		}  // else, due_date < now, so we are late, so we notify of completion
		return $closeToDueDate;
	}
	
		
    /**
     * Send email to client if close to due date. Do not send if far from due date; do not send if client has no email. Do not change status of order.
	 * Note: There are a couple of Yii::t() inside a language change. Make sure strings are available in other languages.
	 */
	public function notify($options) {
		$batch = ArrayHelper::getValue($options, 'batch', false);
		$force = ArrayHelper::getValue($options, 'force', false);
		$simulate = ArrayHelper::getValue($options, 'simulate', false);
		$sent = false;
		$sendmail = isset(Yii::$app->params['sendmail']) ? Yii::$app->params['sendmail'] : true;
		if($simulate)
			$sendmail = false;
		if($this->closeToDueDate() || $force) {
			if($this->notified_at && ($force != 'hard')) { // if already notified, do not send again
				$send_again = Html::a(Yii::t('store', 'Send again'), ['document/sent3', 'id' => $this->id]);
				if(!$batch) Yii::$app->session->setFlash('error', Yii::t('store', 'Client has already be notified on {0}. {1}.', [Yii::$app->formatter->asDateTime($this->notified_at), $send_again]));
				else Yii::trace('Order '.$this->name.' notified on '.$this->notified_at.'.', 'Order::notify');
			} else {
				if(($email = $this->getNotificationEmail()) != '') {
					$lang_before = Yii::$app->language;
					Yii::$app->language = $this->client->lang ? $this->client->lang : 'fr';
					try {
						$destinataire = YII_ENV_DEV ? Yii::$app->params['testEmail'] : $email;
						if($sendmail)
							Yii::$app->mailer->compose('order-completed', ['model' => $this])
							    ->setFrom( Yii::$app->params['fromEmail'] )
							    ->setTo( $destinataire )
								->setReplyTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : Yii::$app->params['replyToEmail'] )
							    ->setSubject(Yii::t('print', $this->document_type).' '.$this->name)
							    ->send();
						$sent = true;
						$this->notified_at = date('Y-m-d H:i:s');
						$this->save();
						if(!$batch) Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
						else Yii::trace('Order '.$this->name.' mail sent to '.$destinataire, 'Order::notify');
					} catch (Swift_TransportException $STe) {
						Yii::error($STe->getMessage(), 'CoverLetter::send::ste');
						if(!$batch) Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
						else Yii::trace('The system could not send mail.', 'Order::notify');
					} catch (Exception $e) {
						Yii::error($e->getMessage(), 'CoverLetter::send::e');				
						if(!$batch) Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
						else Yii::trace('The system could not send mail.', 'Order::notify');
					}
					Yii::$app->language = $lang_before;
				} else {
					Yii::trace('No email for '.$this->name.'('.$this->client->nom.')', 'Order::notify');
				}
			}
		} else {
			if(!$batch) Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has not been notified.').' '.Yii::t('store', 'Due date too far.'));
			else Yii::trace('Due date too far for '.$this->name, 'Order::notify');
		}
		Yii::trace('Notify exists '.($sent ? 'true' : 'false').'.', 'Order::notify');
		return $sent;
	}


    /**
     * If order is completed, send email to client, and create bill if parameters allow it.
	 */
	private function completed() {
		Yii::trace('Order::completed');
		// 1. notify client of completion
		if(Parameter::isTrue('application', 'auto_notify_completion')) {
			if(($email = $this->getNotificationEmail()) != '') {
				if($this->notify(['batch' => true])) {
					$this->setStatus(Order::STATUS_TOPAY);
				} else {
					$this->setStatus(self::STATUS_NOTIFY);
				}
			} else { // no email. Will not be notified of completion.
				$this->setStatus(self::STATUS_TOPAY);
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has not been notified.').' '.Yii::t('store', 'Client has no email.'));
			}
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has not been notified.').' '.Yii::t('store', 'No automatic notification.'));
		}
		// 2. Create bill from order
		if(!$this->bom_bool && Parameter::isTrue('application', 'auto_create_bill')) { // create bill since order is completed
			Yii::trace('auto_create_bill '.$this->id, 'Order::completed');
			$bill = $this->convert();
		}
	}
	
	/**
	 *  Checks whether order has existing work or potential work
	 */
	public function hasWork($start = false) {
		if($this->getWorks()->exists()) { // has work and already created
			return true;
		}

		$todo = false;
		foreach($this->getDocumentLines()->each() as $dl) {
			$todo |= $dl->hasTask();
		}

		if($todo && $start) { // has work and request to create it so we do it
			$work = $this->createWork();
		}

		return $todo; // return if there is something todo.
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
		if( $existing_work = $this->getWorks()->one() ) {
			$existing_work->setStatus(); // this will update this order' status according to the work' status.
			return $existing_work;
		}

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
				$actions[] = '{cancel}';
				$actions[] = '{edit}';
				break;
			case $this::STATUS_OPEN:
				$actions[] = '{cancel}';
				$actions[] = '{edit}';
				$actions[] = '{submit}';
				break;
			case $this::STATUS_WARN:
				$actions[] = '{warn}';
				$actions[] = '{edit}';
			case $this::STATUS_TODO:
			case $this::STATUS_BUSY:
				$actions[] = ($this->getBill() ? '{link:billed}' : '{cancel} {bill}');
				if( $work  ) { // there should always be a work if doc status is TODO or BUSY or WARN
					$actions[] = '{work}';
					$actions[] = '{workterminate}';
				} else
					$actions[] = '{terminate}';
				break;
			case $this::STATUS_NOTIFY:
				$actions[] = '{notify}';
			case $this::STATUS_TOPAY:
			case $this::STATUS_DONE:
				$actions[] = ($this->getBill() ? '{link:billed}' : '{cancel} {bill}');
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

