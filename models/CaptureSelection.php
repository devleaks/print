<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture selection (comma separated list of ids).
 */
class CaptureSelection extends Model
{
	public $selection;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selection'], 'safe'],
		//	['selection', 'match', 'pattern' => '(\d+)(,\s*\d+)*']
        ];
    }

}
