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
 * @property integer $id
 * @property integer $order_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $status
 *
 * @property User $updatedBy
 * @property Order $order
 * @property User $createdBy
 * @property WorkLine[] $workLines
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
	public function updateStatus() {
		$total = $this->getWorkLines()->count();
		$busy = $this->getWorkLines()->andWhere(['status' => Work::STATUS_BUSY])->count();
		$done = $this->getWorkLines()->andWhere(['status' => Work::STATUS_DONE])->count();
		$warn = $this->getWorkLines()->andWhere(['status' => Work::STATUS_WARN])->count();

		$order_status = null;
		if($warn > 0) {
			$this->status = self::STATUS_WARN;
			$order_status = Order::STATUS_WARN;
		} else if($total == $done) {
			$this->status = self::STATUS_DONE;
			$order_status = Order::STATUS_DONE;
		} else if($busy > 0 || $done > 0) {
			$this->status = self::STATUS_BUSY;
			$order_status = Order::STATUS_BUSY;
		} else {
			$this->status = self::STATUS_TODO;
			$order_status = Order::STATUS_TODO;
		}
		$this->save();
		$this->getOrder()->one()->setStatus($order_status);
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
		$this->order->setStatus(Order::STATUS_DONE);
	}

	/**
	 * Generates a list of task icons, one for each task associated with this work.
	 * Each icon is a link to a detail view of the task's progress.
	 */
	public function getTaskIcons($colors = false, $link = false, $button = false) {
		$str = '';
		foreach($this->getWorkLines()->orderBy('order_line_id, position')->each() as $wl) {
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
