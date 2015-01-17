<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "work_line".
 *
 */
class WorkLineDetail extends WorkLine {

	public $order_name;
	public $client_name;
	public $due_date;
	public $item_name;
	public $quantity;
	public $work_width;
	public $work_height;
	public $task_name;
	
	public $steps;
	public $decimals;

	public $cut_minwidth;
	public $cut_maxwidth;
	public $cut_width_count;
	public $cut_width;
	public $margin_width;
	public $cut_minheight;
	public $cut_maxheight;
	public $cut_height_count;
	public $cut_height;
	public $margin_height;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['cut_minwidth', 'cut_maxwidth', 'cut_width_count', 'cut_width', 'margin_width', 'cut_minheight', 'cut_maxheight', 'cut_height_count', 'cut_height', 'margin_height'], 'number'],
	            [['cut_minwidth', 'cut_maxwidth', 'cut_width_count', 'cut_width', 'margin_width', 'cut_minheight', 'cut_maxheight', 'cut_height_count', 'cut_height', 'margin_height'], 'safe'],
        	]
		);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'cut_width' => Yii::t('store', 'Cut Width'),
	        'margin_width' => Yii::t('store', 'Margin Width'),
	        'cut_height' => Yii::t('store', 'Cut Height'),
	        'margin_height' => Yii::t('store', 'Margin Height'),
        ]);
    }

	public function init2() {
		parent::init();
		$cut = 5;
		$this->margin_width = $cut;

		$this->cut_minwidth = $this->documentLine->work_width / 2;
		$this->cut_maxwidth = $this->documentLine->work_width;
		$this->cut_width = $this->documentLine->work_width - $this->margin_width;
		$this->cut_width_count = 2;

		$this->margin_height = $cut;

		$this->cut_minheight = $this->documentLine->work_height / 2;
		$this->cut_maxheight = $this->documentLine->work_height;
		$this->cut_height = $this->documentLine->work_height - $this->margin_height;
		$this->cut_height_count = 2;
	}
}
