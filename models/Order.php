<?php

namespace app\models;

use Yii;
use app\models\Sequence;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

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
		$copy->status = self::STATUS_OPEN;
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

	/**
	 * @inheritdoc
	 */
	public function updatePaymentStatus() {
		Yii::trace('up', 'Order::updatePaymentStatus');
		if($this->bom_bool and $this->parent_id != null) {
			$bill = Bill::findDocument($this->parent_id); // inverse relation
		} else {
			$bill = Bill::findOne(['parent_id' => $this->id]);
		}
		if($bill)
			$bill->updatePaymentStatus();
		else { // regular order
			if($bill = $this->convert()) {
				$this->setStatus(self::STATUS_CLOSED);
				$bill->setStatus($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_SOLDE);
			} else {
				Yii::trace('no bill for order='.$this->id, 'Order::updatePaymentStatus');
				$this->setStatus($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_SOLDE);
			}
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
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = '';

		$work = $this->getWorks()->one();
		if( $show_work && $work ) $ret .= '<p>'.$work->getTaskIcons(true, true, true).'</p>';

		switch($this->status) {
			case $this::STATUS_CREATED:
				$ret .= Html::a($this->getButton($template, 'plus', 'Add Items'), ['/order/document-line/create', 'id' => $this->id], [
					'title' => Yii::t('store', 'Add Items'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					]);
				return $ret;
				break;
			case $this::STATUS_OPEN:
				$ret .= Html::a($this->getButton($template, 'pencil', 'Update'), ['/order/document-line/create', 'id' => $this->id], [
					'title' => Yii::t('store', 'Update'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					]);
				$ret .= ' '.Html::a($this->getButton($template, 'cog', 'Submit Work'), ['/order/document/submit', 'id' => $this->id], [
					'title' => Yii::t('store', 'Submit Work'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Submit work?')
					]);
				$ret .= ' '.Html::a($this->getButton($template, 'remove', 'Cancel'), ['/order/document/cancel', 'id' => $this->id], [
					'title' => Yii::t('store', 'Cancel'),
					'class' => $baseclass . ' btn-danger',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Cancel order?')
					]);
				break;
			case $this::STATUS_WARN:
				$task = null;
				foreach($this->getWorks()->each() as $work) {
					foreach($work->getWorkLines()->where(['status' => Work::STATUS_WARN])->each() as $wl)
						if(!$task) $task = $wl;
				}
				if( $task  )
					$ret .= Html::a($this->getButton($template, 'warning-sign', 'Warning'), ['/work/work-line/detail', 'id' => $task->id], [
						'title' => Yii::t('store', 'Warning'),
						'class' => $baseclass . ' btn-warning',
						'data-method' => 'post',
						]).' ';
			case $this::STATUS_TODO:
			case $this::STATUS_BUSY:
				$ret .= ' '.Html::a($this->getButton($template, 'remove', 'Cancel'), ['/order/document/cancel', 'id' => $this->id], [
					'title' => Yii::t('store', 'Cancel'),
					'class' => $baseclass . ' btn-danger',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Cancel order?')
					]);
				if( $work  ) { // there should always be a work if doc status is TODO or BUSY
					$ret .= ' '.Html::a($this->getButton($template, 'tasks', 'Work'), ['/work/work/view', 'id' => $work->id, 'sort' => 'position'], [
						'title' => Yii::t('store', 'Work'),
						'class' => $baseclass . ' btn-primary',
						'data-method' => 'post',
						]);
					$ret .= ' '.Html::a($this->getButton($template, 'play', 'Terminate'), ['/work/work/terminate', 'id' => $work->id], [
						'title' => Yii::t('store', 'Terminate'),
						'class' => $baseclass . ' btn-primary',
						'data-method' => 'post',
						'data-confirm' => Yii::t('store', 'Terminate all tasks?')
						]);
				} else
					$ret .= ' '.Html::a($this->getButton($template, 'ok-circle', 'Terminate'), ['/order/document/terminate', 'id' => $this->id], [
						'title' => Yii::t('store', 'Terminate'),
						'class' => $baseclass . ' btn-primary',
						'data-method' => 'post',
						'data-confirm' => Yii::t('store', 'Order is ready?')
						]);
				break;
			case $this::STATUS_SOLDE:
			case $this::STATUS_TOPAY:
			case $this::STATUS_DONE:
				$ret .= Html::a($this->getButton($template, 'credit-card', 'Bill To'), ['/order/document/convert', 'id' => $this->id], [
					'title' => Yii::t('store', 'Bill To'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Send bill?')
					]);
				break;
			case $this::STATUS_CANCELLED:
				$ret .= ' <span class="label label-danger">'.Yii::t('store', 'Cancelled').'</span>';
				break;
			case $this::STATUS_CLOSED:
				$bill = $this->bom_bool ?
					Bill::findOne($this->parent_id) // for BOM we set inverse relation, parent_id points to collective bill
					:
					$this->getDocuments()->where(['document_type' => Order::TYPE_BILL])->one(); // or Bill::findOne(['parent_id'=>$this->id]) ?
					
				if( $bill )
					$ret .= ' '.Html::a('<span class="label label-success">'.Yii::t('store', 'Billed').'</span>',
										['/order/document/view', 'id' => $bill->id], ['title' => Yii::t('store', 'View Bill'), 'data-method' => 'post']);
				else
					$ret .= ' <span class="label label-success">'.Yii::t('store', 'Billed').'</span>';

				break;
		}
		return $ret . parent::getActions($baseclass, $show_work, $template);
	}
	
}

