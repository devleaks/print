<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

/**
 * This is the model class for table "work".
 *
 */
class Work extends _Work
{
	/** */
	const STATUS_TODO = 'TODO';
	/** */
	const STATUS_BUSY = 'BUSY';
	/** */
	const STATUS_DONE = 'DONE';
	/** */
	const STATUS_WARN = 'WARN';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
                'userstamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                        ],
                        'value' => function() { return Yii::$app->user->id;},
                ],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {	// hacked to return proper document type
		$temp = $this->hasOne(Document::className(), ['id' => 'document_id'])->one();
		if(!$temp)
			return $this->hasOne(Document::className(), ['id' => 'document_id']);
		switch($temp->document_type) {
			case Document::TYPE_ORDER:
				return $this->hasOne(Order::className(), ['id' => 'document_id']);
				break;
			case Document::TYPE_TICKET:
				return $this->hasOne(Ticket::className(), ['id' => 'document_id']);
				break;
			default:
				return $this->hasOne(Document::className(), ['id' => 'document_id']);
				break;
		}
    }

	/** 
	 * Assign a color for each status
	 */
	public static function getStatusColors() {
		// default  primary  success  info  warning  danger
		return [
			self::STATUS_TODO => 'primary',
			self::STATUS_BUSY => 'info',
			self::STATUS_DONE => 'success',
			self::STATUS_WARN => 'warning',
		];
	}
	
	/** 
	 * Return (name,localized display value) pairs of all statuses
	 */
	public static function getStatuses() {
		return [
			self::STATUS_TODO => Yii::t('store', self::STATUS_TODO),
			self::STATUS_BUSY => Yii::t('store', self::STATUS_BUSY),
			self::STATUS_DONE => Yii::t('store', self::STATUS_DONE),
			self::STATUS_WARN => Yii::t('store', self::STATUS_WARN),
		];
	}
	
	/** 
	 * Update status depending on children WorkLines
	 */
	
	/** Utility function: Returns Order Status to set for supplied Work Status or for current Work model */
	public function getOrderStatus($work_status = null) {
		if($work_status == null) $work_status = $this->status;
		
		$order_status = null;
		switch($work_status) {
			case Work::STATUS_WARN: $order_status = Order::STATUS_WARN; break;
			case Work::STATUS_DONE: $order_status = Order::STATUS_DONE; break;
			case Work::STATUS_BUSY: $order_status = Order::STATUS_BUSY; break;
			case Work::STATUS_TODO: $order_status = Order::STATUS_TODO; break;
		}
		return $order_status;
	}
	
	public function updateStatus() {
		$total = $this->getWorkLines()->count();
		$busy = $this->getWorkLines()->andWhere(['status' => Work::STATUS_BUSY])->count();
		$done = $this->getWorkLines()->andWhere(['status' => Work::STATUS_DONE])->count();
		$warn = $this->getWorkLines()->andWhere(['status' => Work::STATUS_WARN])->count();

		$order_status = null;
		if($warn > 0) {
			$this->status = self::STATUS_WARN;
			$order_status = $this->getOrderStatus(Work::STATUS_WARN);
		} else if($total == $done) {
			$this->status = self::STATUS_DONE;
			$order_status = $this->getOrderStatus(Work::STATUS_DONE);
		} else if($busy > 0 || $done > 0) {
			$this->status = self::STATUS_BUSY;
			$order_status = $this->getOrderStatus(Work::STATUS_BUSY);
		} else {
			$this->status = self::STATUS_TODO;
			$order_status = $this->getOrderStatus(Work::STATUS_TODO);
		}
		$this->save();
		$this->getDocument()->one()->setStatus($order_status);
	}

	
	public static function getBadge($id) {
		$where = Order::getDateClause(intval($id));
		$color = [
			self::STATUS_DONE => 'success',
			self::STATUS_BUSY => 'warning',
			self::STATUS_TODO => 'primary',
			self::STATUS_WARN => 'danger',
		];
		$icon = [
			self::STATUS_DONE => 'ok',
			self::STATUS_BUSY => 'inbox',
			self::STATUS_TODO => 'play-circle',
			self::STATUS_WARN => 'warning-sign',
		];
		$str = '';
		foreach(array(self::STATUS_DONE, self::STATUS_BUSY, self::STATUS_TODO, self::STATUS_WARN) as $status) {
			$cnt = self::find()
				->andWhere($where)
				->andWhere(['status' => $status])
				->count();
			$str .= '<span class="badge alert-'.$color[$status].'"><i class="glyphicon glyphicon-'.$icon[$status].'"></i> '.$cnt.'</span>';
		}
		return $str;
	}
	
	/**
	 * Delete cascade all children WorkLines and this Work.
	 */
	public function deleteCascade() {
		foreach($this->getWorkLines()->each() as $wl)
			$wl->deleteCascade();

		$this->delete();
	}

	/**
	 * Terminate all WorkLines and this Work.
	 */
	public function terminate() {
		foreach($this->getWorkLines()->each() as $wl) {
			$wl->status = Work::STATUS_DONE;
			$wl->save();
		}
		$this->status = Work::STATUS_DONE;
		$this->save();
		$this->document->setStatus(Order::STATUS_DONE);
	}

	/**
	 * Generates a list of task icons, one for each task associated with this work.
	 * Each icon is a link to a detail view of the task's progress.
	 */
	public function getTaskIcons($colors = false, $link = false, $button = false) {
		$str = '';
		foreach($this->getWorkLines()->orderBy('document_line_id, position')->each() as $wl) {
			$icon = $wl->task->icon;
			if($colors)
				$color = $wl->status == Work::STATUS_DONE ? 'success' :
							($wl->status == Work::STATUS_TODO ? 'primary' :
								($wl->status == Work::STATUS_WARN ? 'warning' : 'info'));
			else
				$color = 'default';
			
			if($link)
				$str .= Html::a(Icon::show($icon, ['class'=>'fa'. ($button ? '':' text-'.$color)]),
						Url::to(['/work/work-line/detail', 'id' => $wl->id]), [
							'class'=>'fa'.($button ? ' btn btn-':'-2x text-').$color
						]
				) . ' ';
			else
				$str .= Icon::show($icon, ['class'=>'fa-2x text-'.$color]);
		}
		return $str;
	}
	
	public function getStatusLabel() {
		$color = $this->getStatusColors();
		return '<span class="label label-'.$color[$this->status].'">'.Yii::t('store', $this->status).'</span>';
	}
	
	public static function isValidStatus($status) {
		return in_array($status, array_keys(self::getStatuses()));
	}
}
