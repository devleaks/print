<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "item_task".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $note
 * @property integer $task_id
 *
 * @property Task $task
 * @property Item $item
 */
class ItemTask extends _ItemTask
{
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
        ];
    }

}
