<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

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
		if($this->status == self::STATUS_DONE || $this->status == self::STATUS_SOLDE) {
			$solde = $this->getBalance();
			Yii::trace('solde='.$solde, 'Ticket::updatePaymentStatus');
			$this->setStatus($solde < 0.01 ? self::STATUS_CLOSED : self::STATUS_SOLDE);
		} // otherwise, we leave the status as it is
	}

    /**
     * @inheritdoc
	 */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = '';

		$work = $this->getWorks()->one();
		if( $show_work && $work ) $ret .= '<p>'.$work->getTaskIcons(true, true, true).'</p>';

		switch($this->status) {
			case $this::STATUS_OPEN:
				$ret .= Html::a($this->getButton($template, 'pencil', 'Modify'), ['/order/document-line/create', 'id' => $this->id], [
					'title' => Yii::t('store', 'Modify'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					]);
				$ret .= ' '.Html::a($this->getButton($template, 'tasks', 'Submit Work'), ['/order/document/submit', 'id' => $this->id], [
					'title' => Yii::t('store', 'Submit Work'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Submit work?')
					]);
				$ret .= ' '.Html::a($this->getButton($template, 'remove', 'Cancel'), ['/order/document/cancel', 'id' => $this->id], [
					'title' => Yii::t('store', 'Cancel'),
					'class' => $baseclass . ' btn-warning',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Cancel order?')
					]);
				break;
			case $this::STATUS_TODO:
			case $this::STATUS_BUSY:
				if( $work  ) {
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
			case $this::STATUS_SOLDE:
				$ret .= ' '.Html::a($this->getButton($template, 'ok-sign', 'Receive'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-primary', 'title' => Yii::t('store', 'Receive')]);
				break;
			case $this::STATUS_CLOSED:
			case $this::STATUS_CANCELLED:
				$ret .= ' <span class="label label-success">'.Yii::t('store', $this->status).'</span>';
				break;
		}
		$ret .= ' '.Html::a($this->getButton($template, 'print', 'Print'), ['/order/document/print', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Print')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/document/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'View')]);
		return $ret;
	}

}