<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class CaptureEmail extends Model
{
	public $id;
	public $email;
	public $save;
	public $body;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email', 'save', 'body', 'id'], 'safe'],
            [['id'], 'number'],
            [['email'], 'string', 'max' => 80],
            [['body'], 'string', 'max' => 1000],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('store', 'Email'),
            'save' => Yii::t('store', 'Save Email?'),
            'body' => Yii::t('store', 'Message'),
        ];
    }
}
