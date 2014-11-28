<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sequence_data".
 *
 * @property string $sequence_name
 * @property integer $sequence_increment
 * @property integer $sequence_min_value
 * @property string $sequence_max_value
 * @property string $sequence_cur_value
 * @property integer $sequence_cycle
 */
class Sequence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sequence_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sequence_name'], 'required'],
            [['sequence_increment', 'sequence_min_value', 'sequence_max_value', 'sequence_cur_value', 'sequence_cycle'], 'integer'],
            [['sequence_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sequence_name' => Yii::t('store', 'Sequence Name'),
            'sequence_increment' => Yii::t('store', 'Sequence Increment'),
            'sequence_min_value' => Yii::t('store', 'Sequence Min Value'),
            'sequence_max_value' => Yii::t('store', 'Sequence Max Value'),
            'sequence_cur_value' => Yii::t('store', 'Sequence Cur Value'),
            'sequence_cycle' => Yii::t('store', 'Sequence Cycle'),
        ];
    }

	/** there are no lock, so don't use concurrency on those:
	 */
	public static function currval($name) {
		$seq = self::find()->where(['sequence_name' => $name])->one();
		return $seq ? $seq->sequence_cur_value : null;
	}

	/** we don't even test for cycles, etc. @todo
	 */
	public static function nextval($name) {
		$seq = self::find()->where(['sequence_name' => $name])->one();
		if ( $seq ) {
			$seq->sequence_cur_value += $seq->sequence_increment;
			$seq->save();
			return $seq->sequence_cur_value;
		}
		return null;
	}
}
