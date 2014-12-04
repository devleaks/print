<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Bid extends Document
{
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace('defaultScope', 'app');
        $query->andWhere(['document_type' => self::TYPE_BID]);
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
				$ret .= ' '.Html::a($this->getButton($template, 'ok', 'Convert to Order'), ['/order/document/convert', 'id' => $this->id], [
					'title' => Yii::t('store', 'Convert to Order'),
					'class' => $baseclass . ' btn-success',
					'data-method' => 'post',
					'data-confirm' => Yii::t('store', 'Convert to order?')
					]);
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
		$ret .= ' '.Html::a($this->getButton($template, 'print', 'Print'), ['/order/document/print', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Print')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/document/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'View')]);
		return $ret;
	}

}