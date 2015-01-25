<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item".
 */
class Item extends _Item
{
	/** normal item, 1 per order line only */
	const STATUS_ACTIVE = 'ACTIVE';
	/** item can no longer be selected */
	const STATUS_RETIRED = 'INACTIVE';
	
	/** For rebate and majoration */
	const STATUS_EXTRA = 'EXTRA';

	/** special item references */
	const TYPE_CHROMALUXE = '1';
	const TYPE_MISC       = '#';

	/** item reference for special line "REMISE" */
	const TYPE_REBATE = '%';
	const TYPE_CREDIT = 'Credit';
	const TYPE_REFUND = 'Refund';

	/** */
	const EXTRA_REBATE_FIX		= '-';
	const EXTRA_REBATE_PCT		= '-%';
	const EXTRA_SUPPLEMENT_FIX	= '+';
	const EXTRA_SUPPLEMENT_PCT	= '+%';

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
                'binarystatus' => [ // remove 'fa-' prefix from icon names
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => 'status',
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'status',
                        ],
                        'value' => function($event) { return $this->status && ($this->status != self::STATUS_RETIRED) ? self::STATUS_ACTIVE : self::STATUS_RETIRED;},
                ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])
            ->viaTable('item_task', ['item_id' => 'id']);
    }

	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @return array()
	 */
	public static function getStatuses() {
		return [
			self::STATUS_ACTIVE => Yii::t('store', self::STATUS_ACTIVE),
			self::STATUS_RETIRED => Yii::t('store', self::STATUS_RETIRED),
		];
	}
	
	/**
	 * returns associative array of value, display name value for supplied Item attribute
	 *
	 * @param $which string Item attribute
	 *
	 * @return array()
	 */
	protected static function getDistinctColumValues($which) {
		return ArrayHelper::map(self::find()->select($which)->distinct()->asArray()->all(), $which, $which);
	}
	
	/**
	 * returns associative array of value, display name value for Item categorie attribute
	 *
	 * @return array()
	 */
	public static function getCategories() {
		return self::getDistinctColumValues('yii_category');
	}
	
	/**
	 * returns associative array of id, item display name value for Item oin supplied categorie
	 *
	 * @param $category string Item categorie
	 * @param $addnull string Whether to prepend null (empty) element
	 *
	 * @return array()
	 */
	public static function getListForCategory($category, $addnull = false) {
		$arr = ArrayHelper::map(self::find()->where(['yii_category' => $category, 'status' => 'ACTIVE'])->asArray()->all(), 'id', 'libelle_long');
		return $addnull ? ['' =>  ''] + $arr : $arr;
	}

	/**
	 * create work line elements for Item from its associated tasks.
	 *
	 * @param $work Work model to attach WorkLine object to.
	 * @param $order_line DocumentLine model in which Item is used.
	 */
	public function createTasks($work, $order_line) {
		$wlCreated = false;
		
		$defaultTaskId = Parameter::getIntegerValue('application', 'default_task');
	
		foreach($this->getTasks()->each() as $task) {	// get each item in line
			$wl = new WorkLine();
			$wl->work_id = $work->id;
			$wl->task_id = $task->id;
			$wl->item_id = $this->id;
			$wl->document_line_id = $order_line->id;
			$wl->due_date = $order_line->due_date;
			$wl->priority = $order_line->priority;
			if($itp = ItemTask::findOne(['item_id' => $this->id, 'task_id' => $task->id]))
				$wl->position = $itp->position;
			else
				$wl->position = 0;
			$wl->status = Work::STATUS_TODO;
			$wl->save();
			$wlCreated = true;
		}
		if($defaultTaskId !== null && !$wlCreated) {
			$wl = new WorkLine();
			$wl->work_id = $work->id;
			$wl->task_id = $defaultTaskId;
			$wl->item_id = $this->id;
			$wl->document_line_id = $order_line->id;
			$wl->due_date = $order_line->due_date;
			$wl->priority = $order_line->priority;
			$wl->position = 100;
			$wl->status = Work::STATUS_TODO;
			$wl->save();
		}
	}

	/**
	 * @return boolean whether price needs special calculation
	 */	
	public function hasPriceComputation() {
		//return in_array($this->categorie, ['ChromaLuxe', 'Cadre', 'Montage', 'ChromaSupport', 'Support']);
		return in_array($this->yii_category, ['ChromaLuxe', 'Tirage', 'Cadre', 'Montage', 'Canvas', 'Support', 'Protection']);
	}


	public function isSpecial() {
		return $this->yii_category == 'SPECIAL';
	}
	

	public function getPriceCalculator() {
		switch(strtolower($this->yii_category)) {
			case 'chromaluxe':
				return new ChromaLuxePriceCalculator();
				break;
			case 'cadre':
				if($this->fournisseur == 'Nielsen')
					return new NielsenPriceCalculator(['item' => $this]);
				else if ($this->fournisseur == 'Exhibit')
					return new ExhibitPriceCalculator(['item' => $this]);
				else
					return new PriceCalculator(['item' => $this, 'type' => PriceCalculator::PERIMETER]);
				break;
			case 'support':
			case 'uv':
				return new PriceCalculator(['item' => $this, 'type' => PriceCalculator::SURFACE]);
				break;
		}
		return null;
	}
}
