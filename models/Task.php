<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 */
class Task extends _Task
{
	/** */
	const STATUS_ACTIVE = 'ACTIVE';

	/** */
	const STATUS_INACTIVE = 'INACTIVE';


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
                'iconname' => [ // remove 'fa-' prefix from icon names
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => 'icon',
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'icon',
                        ],
                        'value' => function($event) { return str_replace('fa-', '', $this->icon);},
                ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])
                    ->viaTable('item_task', ['task_id' => 'id']);
    }

	/**
	 * Returns array of possible Task status value.
	 *
	 * @return Array() Array of (status,localized display names)
	 */
	public static function getStatuses() {
		return [
			self::STATUS_ACTIVE => Yii::t('store', self::STATUS_ACTIVE),
			self::STATUS_INACTIVE => Yii::t('store', self::STATUS_INACTIVE),
		];
	}
	
}
