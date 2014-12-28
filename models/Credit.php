<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Credit extends Order
{
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_CREDIT, 'Credit::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_CREDIT]);
    }


    /**
     * @inheritdoc
     */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = '';
		switch($this->status) {
			case $this::STATUS_OPEN:
				$ret .= Html::a($this->getButton($template, 'send', 'Send Credit Note'), ['/order/document/sent', 'id' => $this->id],[
					'title' => Yii::t('store', 'Send Credit Note'),
					'class' => $baseclass . ' btn-primary',
					'data-confirm' => Yii::t('store', 'Send credit note?')
				]);
				break;
			case $this::STATUS_TOPAY:
				$ret .= Html::a($this->getButton($template, 'envelope', 'Paid'), ['/order/document/paid', 'id' => $this->id],[
					'title' => Yii::t('store', 'Paid'),
					'class' => $baseclass . ' btn-primary',
					'data-confirm' => Yii::t('store', 'Paiement sent?')
				]);
				break;
			case $this::STATUS_CLOSED:
				$ret .= '<span class="label label-success">'.Yii::t('store', 'Paiement Sent').'</span>';
				break;
		}
		$ret .= ' '.Html::a($this->getButton($template, 'print', 'Print'), ['/order/document/print', 'id' => $this->id], ['target' => '_blank', 'class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'Print')]);
		//$ret .= ' '.Html::a($this->getButton($template, 'envelope', 'Send'), ['/order/document/send', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		$ret .= ' '.Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info', 'title' => Yii::t('store', 'View')]);
		return $ret;
	}

}