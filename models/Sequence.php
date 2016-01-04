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
	 *  Reset numbers at first use of a new year
	 */
	public function reset() {
		if($this->sequence_cycle) {
			$curyear = date('Y'); // YYYY
			if( ($this->sequence_year != $curyear) // if next year
			 || ($this->sequence_cur_value > $this->sequence_max_value)) {	// or value too large
				$this->sequence_cur_value = $this->sequence_min_value;			// we reset
				$this->sequence_year = $curyear;
				return $this->save();
			}
		}
		return true;
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
		if($seq) $seq->reset();
		return $seq ? $seq->sequence_cur_value : null;
	}

	/** we don't even test for cycles, etc.
	 */
	public static function nextval($name) {
		$seq = self::find()->where(['sequence_name' => $name])->one();
		if($seq) $seq->reset();
		if ( $seq ) {
			$seq->sequence_cur_value += $seq->sequence_increment;
			$seq->save();
			return $seq->sequence_cur_value;
		}
		return null;
	}
	
	/** Generate belgian structured communication from number (<10^10).
	 * @param $s number
	 * @return string Structured communication as " +++ 123/4567/890XX +++"
	 */
	public static function  commStruct($s = 0) { 
        $d = sprintf("%010s",$s);
        $modulo = (bcmod($s,97)==0?97:bcmod($s,97));
        return sprintf("+++ %s/%s/%s%02d +++",substr($d,0,3),substr($d,3,4),substr($d,7,3),$modulo);
	}
	
	public static function getReservationNumber($len = 5, $valid_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
		$rn = '';
		for ($i = 0; $i < $len; $i++) {
      		$rn .= $valid_chars[rand(0, strlen($valid_chars) - 1)];
		}
		return $rn;
	}

}