<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class to cature email address.
 */
class History extends _History
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
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
                'userstamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['performer_id'],
                        ],
                        'value' => function() { return Yii::$app->user->id;},
                ],
        ];
    }

    /**
     * Create & save history record
     */
    public static function record($model, $action, $summary, $payload, $note)
    {
        $history = new self();
		$history->object_type = $model->className();
		$history->object_id = $model->id;
		$history->action = $action;
		$history->summary = $summary;
		if($payload)
			$history->payload = Json::encode($model->attributes);
		if($note)
			$history->note = $note;		
		$history->save();
    }
}
