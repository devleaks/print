<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

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
		if( $existing_next = $this->find()->andWhere(['parent_id' => $this->id])->andWhere(['document_type' => self::TYPE_ORDER])->one() )
			return $existing_next;
		$copy = $this->deepCopy( $ticket ? self::TYPE_TICKET : self::TYPE_ORDER);
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


    /**
     * @inheritdoc
	 */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = '';
		switch($this->status) {
			case $this::STATUS_OPEN:
				$ret .= Html::a($this->getButton($template, 'pencil', 'Modify'), ['/order/document-line/create', 'id' => $this->id], [
					'title' => Yii::t('store', 'Modify'),
					'class' => $baseclass . ' btn-primary',
					'data-method' => 'post',
					]);
				$ret .= ' <div class="btn-group"><button type="button" class="'.$baseclass.' btn-success dropdown-toggle" data-toggle="dropdown">'.
		        	$this->getButton($template, 'ok', 'Convert to Order'). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
						'<li>'.Html::a(Yii::t('store', 'Convert to order'),
										['/order/document/convert', 'id' => $this->id],
										['title' => Yii::t('store', 'Convert to order'),
											'data-method' => 'post',
											'data-confirm' => Yii::t('store', 'Convert to order?')]
						).'</li>'.
						'<li>'.Html::a(Yii::t('store', 'Convert to sale'),
										['/order/document/convert', 'id' => $this->id, 'ticket' => true],
										['title' => Yii::t('store', 'Convert to sale'),
											'data-method' => 'post',
											'data-confirm' => Yii::t('store', 'Convert to sale?')]
						).'</li>'.
     				'</ul></div>';
				/*$ret .= ' '.Html::a($this->getButton($template, 'ok', 'Convert to Order'), ['/order/document/convert', 'id' => $this->id], [
					'title' => Yii::t('store', 'Convert to Order'),
					'class' => $baseclass . ' btn-success',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Convert to order?')
					]);*/
				$ret .= ' '.Html::a($this->getButton($template, 'remove', 'Cancel'), ['/order/document/cancel', 'id' => $this->id], [
					'title' => Yii::t('store', 'Cancel'),
					'class' => $baseclass . ' btn-warning',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Cancel order?')
					]);
				break;
			case $this::STATUS_CLOSED:
				if( $order = $this->getDocuments()->where(['document_type' => Order::TYPE_ORDER])->one() )
					$ret .= ' '.Html::a('<span class="label label-success">'.Yii::t('store', 'Order Placed').'</span>',
										['/order/document/view', 'id' => $order->id], ['data-method' => 'post', 'title' => Yii::t('store', 'View Order')]);
				else
					$ret .= ' <span class="label label-success">'.Yii::t('store', 'Order Placed').'</span>';
				break;
			case $this::STATUS_CANCELLED:
				$ret .= ' <span class="label label-primary">'.Yii::t('store', 'Cancelled').'</span>';
				break;
		}
		return $ret . parent::getActions($baseclass, $show_work, $template);
	}

}