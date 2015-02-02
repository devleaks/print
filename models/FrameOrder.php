<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class FrameOrder extends Model
{
	public $reference;
	public $due_date;
	public $provider;
	public $provider_email;
	public $item;
	public $quantity;
	public $width;
	public $height;
	public $note;
	public $work_line_id;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference', 'due_date', 'provider_email', 'provider_email', 'item', 'width', 'height', 'note', 'quantity', 'work_line_id' ], 'safe'],
            [['reference', 'due_date', 'provider_email', 'provider_email', 'item', 'width', 'height', 'note' ], 'string'],
			[['quantity', 'work_line_id'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'reference' => Yii::t('store', 'Our Reference'),
			'due_date' => Yii::t('store', 'Due Date'),
			'provider' => Yii::t('store', 'Provider'),
			'provider_email' => Yii::t('store', 'Email'),
			'item' => Yii::t('store', 'Item'),
			'width' => Yii::t('store', 'Work Width'),
			'height' => Yii::t('store', 'Work Height'),
			'note' => Yii::t('store', 'Note'),
        ];
    }
}
