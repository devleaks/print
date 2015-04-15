<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture searchess.
 */
class CaptureSearch extends Model
{
	public $search;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search'], 'required'],
            [['search'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'search' => Yii::t('store', 'Search'),
        ];
    }
}
