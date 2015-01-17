<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class Cut extends Model
{
	public $id;
	public $work_line_id;
	public $length;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'work_line_id'], 'integer'],
            [['length'], 'number'],
        ];
    }

}
