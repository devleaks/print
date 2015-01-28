<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for new segments.
 */
class Master extends _Master
{
	/** */
	const DEFAULT_SIZE = 200;

	/** */
	const MINIMUM_SIZE = 20;


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


	public static function createNew($size = self::DEFAULT_SIZE) {
		$m = new Master([
			'work_length' => $size,
		]);
		$m->save();
		$m->refresh();
		return $m;
	}
	

	public static function deleteUnusedMasters() {
		$used_masters = Segment::find()->select('master_id')->distinct()->column();
		$empty_masters = Master::find()->andWhere(['work_length' => self::DEFAULT_SIZE])->andWhere(['not',['id' => $used_masters]]);
		foreach($empty_masters->each() as $m) $m->delete();
	}


	public static function getUnusedMasters() {
		$used_masters = Segment::find()->select('master_id')->distinct()->column();
		return Master::find()->andWhere(['not', ['work_length' => self::DEFAULT_SIZE]])->andWhere(['not',['id' => $used_masters]]);
	}


	public function getUsed() {
		return $this->getSegments()->sum('work_length');
	}
	

	public static function splitLessUsedMasters() {
		$min_used = self::DEFAULT_SIZE - self::MINIMUM_SIZE;
		foreach(Master::find()->each() as $m)
			if($m->used() < $min_used)
				$m->split();
	}


	public function split() {
		$used = $this->getUsed();
		$orig = $this->work_length;
		$this->work_length = $used;
		$this->save();
		if(($orig - $used) > self::MINIMUM_SIZE) { // we only keep bits larger then minimum size
			$split = self::createNew($orig - $used);
			$split->save();
			return $split;
		}
		return $this;
	}
}
