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
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLines() // modified to have the global rebate line always LAST
    {
		$item = Item::findOne(['reference' => Item::TYPE_REBATE]);
		$special_line_query = DocumentLine::find()
							->andWhere(['document_id' => $this->id])
							->andWhere(['item_id' => $item->id]);
							
		$query = DocumentLine::find()
							->andWhere(['document_id' => $this->id])
							->andWhere(['!=', 'item_id', $item->id])
							->union($special_line_query);

        return $query;
        // return $this->hasMany(DocumentLine::className(), ['document_id' => 'id']); // this is the original line
    }

	/**
	 * @inheritdoc
	 */
	protected function statusUpdated() {
		Yii::trace('up', 'Order::statusUpdated()');
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

				Yii::$app->mailer->compose()
				    ->setFrom( Yii::$app->params['fromEmail'] )						// From label could be a param
				    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $this->client->email )	// <=== FORCE DEV EMAIL TO TEST ADDRESS
				    ->setSubject(Yii::t('store', $this->document_type).' '.$this->name)				// @todo: msg dans la langue du client
					->setTextBody(Yii::t('store', 'Your {document} is ready.', [
    									'document' => strtolower(Yii::t('store', $this->document_type)).' '.$this->name]))
				    ->send();

				Yii::$app->language = $lang_before;
				Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has no email address. No notification mail sent.'));
			}
		}
		// 2. Create bill from order
		if(!$this->bom_bool && Parameter::isTrue('application', 'auto_send_bill')) { // create bill since order is completed
			Yii::trace('auto_send_bill '.$this->id, 'Order::completed');
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
				$ret .= ' '.Html::a($this->getButton($template, 'tasks', 'Submit Work'), ['/order/document/submit', 'id' => $this->id], [
					'title' => Yii::t('store', 'Submit Work'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Submit work?')
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
				if( $work  ) { // there should always be a work if doc status is TODO or BUSY
					$ret .= Html::a($this->getButton($template, 'tasks', 'Work'), ['/work/work/view', 'id' => $work->id], [
						'title' => Yii::t('store', 'Work'),
						'class' => $baseclass . ' btn-primary',
						'data-method' => 'post',
						]);
					$ret .= ' '.Html::a($this->getButton($template, 'ok-circle', 'Terminate'), ['/work/work/terminate', 'id' => $work->id], [
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
			case $this::STATUS_DONE:
				$ret .= Html::a($this->getButton($template, 'credit-card', 'Bill To'), ['/order/document/convert', 'id' => $this->id], [
					'title' => Yii::t('store', 'Bill To'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Send bill?')
					]);
				break;
			case $this::STATUS_CANCELLED:
				$ret .= ' <span class="label label-warning">'.Yii::t('store', 'Cancelled').'</span>';
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
		$ret .= ' '.Html::a($this->getButton($template, 'print', 'Print'), ['/order/document/print', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Print')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/document/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'tag', 'Labels'), ['/order/document/labels', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Labels')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/document/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'View')]);
		return $ret;
	}
	
}

