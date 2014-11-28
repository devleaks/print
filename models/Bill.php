<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Bill extends Document
{
    /**
     * @inheritdoc
	 */
	public static function defaultScope($query)
    {
		Yii::trace('defaultScope Bill', 'app');
        $query->andWhere(['order_type' => self::TYPE_BILL]);
    }

	/**
	 *
	 */
	public function send() {
		$this->status = Document::STATUS_NOTE;
		$this->save();
	}
	
	
	protected static function append($to, $src, $sep, $max = 160) {
		return (strlen($src)+strlen($sep)+strlen($to)) < $max ? $to.$sep.$src : $to ;
	}
	
	/**
	 *
	 */
	public function createFromBoms($boms) {
		if(!count($boms) > 0)
			return null;
		$boms = Order::find()->where(['bom_bool' => true, 'id' => $boms])->orderBy('created_at');
		if($boms->exists()) {
			$model = null;
			foreach($boms->each() as $bom) {
				$line = 1;
				if(! $model) {
					$model = new Bill();
					$model->order_type = self::TYPE_BILL;
					$model->id = null;
					$model->client_id = $bom->client_id;
					$model->due_date = $bom->due_date;
					$model->name = substr($bom->due_date,0,4).'-'.Sequence::nextval('order_number');
					$model->note = $bom->name;
					$model->status = self::STATUS_OPEN;
					$model->save();
				} else {
					$model->note = self::append($model->note, $bom->name, ',', 160);
					$model->save();
				}
				// add order lines from bom to bill
				foreach($bom->getOrderLines()->each() as $ol) {
					/*
					if($ol->item->reference === Item::TYPE_REBATE) { // global rebate line not allowed in BOM
						foreach($boms->each() as $bom) {
							$bom->setStatus(Document::STATUS_DONE);
							$bom->parent_id = null;
							$bom->save();
						}
						$model->deleteCascade();
						return null;
					}
					*/
					$bl = $ol->deepCopy($model->id);
					$bl->note = self::append($bl->note,
											($ol->item->reference === Item::TYPE_REBATE)
										? '/'.Yii::t('store', $bl->extra_htva > 0 ? 'Supplement' : 'Rebate').' '.Yii::t('store', 'for').' '.$bom->name.'.'
										: '/'.$bom->name.':'.$line.'.'
								, ' ', 160);
					$bl->save();
					$line++;
				}
				
				$last_date = $bom->due_date;
				$bom->setStatus(Document::STATUS_CLOSED);
				$bom->parent_id = $model->id; // inverse relation, should be children...
				$bom->save();
			} // foreach BOM
			$model->due_date = $last_date;
			$model->updatePrice(false);	// do NOT update potential REBATE lines
			$model->save();
			if(Parameter::isTrue('application', 'auto_send_bill')) {
				$model->send();
			}
			return $model;
		}	
		return null;
	}

    /**
     * @inheritdoc
	 */
	public function canModify() {
		/** once a bill has been emitted, it cannot be changed. */
		return ($this->status == Document::STATUS_OPEN || $this->status == Document::STATUS_CREATED);
	}

    /**
     * @inheritdoc
	 */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = '';
		switch($this->status) {
			case $this::STATUS_OPEN:
				$ret .= Html::a($this->getButton($template, 'credit-card', 'Send Bill'), ['/order/order/sent', 'id' => $this->id], [
					'title' => Yii::t('store', 'Send Bill'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Send bill?')
					]);
				break;
			case $this::STATUS_NOTE:
				$ret .= Html::a($this->getButton($template, 'euro', 'Paiement Received'), ['/order/order/paid', 'id' => $this->id], [
					'title' => Yii::t('store', 'Paiement Received'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Paiement received?')
					]);
				break;
			case $this::STATUS_CLOSED:
				$ret .= '<span class="label label-success">'.Yii::t('store', 'Paiement Received').'</span>';
				break;
		}
		$ret .= ' '.Html::a($this->getButton($template, 'print', 'Print'), ['/order/order/print', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Print')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/order/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/order/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'View')]);
		return $ret;
	}

}