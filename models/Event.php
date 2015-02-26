<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for new segments.
 */
class Event extends _Event
{
	/** normal item, 1 per order line only */
	const STATUS_ACTIVE = 'ACTIVE';
	/** item can no longer be selected */
	const STATUS_RETIRED = 'INACTIVE';
	
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

}
