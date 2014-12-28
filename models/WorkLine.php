<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "work_line".
 *
 */
class WorkLine extends _WorkLine
{
	public $total;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for computation.
            [['total'], 'number'],
            [['total'], 'safe'],
       ]);
    }

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
	 * Update status and reports to parent Work model
	 */
	protected function statusUpdated() {
		$this->getWork()->one()->setStatus();
	}
	
	/** 
	 * Update status and reports to parent Work model
	 */
	public function setStatus($status) {
		$this->status = $status;
		if($status == Work::STATUS_DONE || $status == Work::STATUS_BUSY)
			$this->setStatusOfPreceedingTasks(Work::STATUS_DONE);
		$this->save();
		$this->statusUpdated();
	}

	/** 
	 * Update status of tasks preceeding this one
	 */
	protected function setStatusOfPreceedingTasks($status) {
		$preceedingTasks = WorkLine::find()
			->andWhere(['document_line_id' => $this->document_line_id])
			->andWhere(['<', 'position', $this->position])
		;
		foreach($preceedingTasks->each() as $wl) {
			$wl->status = $status;
			$wl->save();
		}
	}
	
	/**
	 * Delete this WorkLine.
	 */
	public function deleteCascade() {
		$this->delete();
	}

	/**
	 * Get nice, colored status label for current WorkLine.
	 * @retrurn string HTML status label fragment
	 */
	public function getStatusLabel() {
		$color = Work::getStatusColors();
		return '<span class="label label-'.$color[$this->status].'">'.Yii::t('store', $this->status).'</span>';
	}


	public static function getBadge($id) {
		$where = Order::getDateClause(intval($id));
		$color = [
			Work::STATUS_DONE => 'success',
			Work::STATUS_BUSY => 'warning',
			Work::STATUS_TODO => 'primary',
			Work::STATUS_WARN => 'danger',
		];
		$icon = [
			Work::STATUS_DONE => 'ok',
			Work::STATUS_BUSY => 'inbox',
			Work::STATUS_TODO => 'play-circle',
			Work::STATUS_WARN => 'warning-sign',
		];
		$str = '';
		foreach(array(Work::STATUS_DONE, Work::STATUS_BUSY, Work::STATUS_TODO, Work::STATUS_WARN) as $status) {
			$cnt = self::find()
				->andWhere($where)
				->andWhere(['status' => $status])
				->count();
			$str .= '<span class="badge alert-'.$color[$status].'"><i class="glyphicon glyphicon-'.$icon[$status].'"></i> '.$cnt.'</span>';
		}
		return $str;
	}
	
}
