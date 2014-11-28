<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_line_detail".
 *
 * @property integer $id
 * @property integer $order_line_id
 * @property string $detail_type
 * @property string $type_chroma
 * @property integer $renfort
 * @property integer $coin_arrondis
 * @property double $work_length
 * @property integer $frame_id
 * @property integer $filmuv_id
 *
 * @property Item $filmuv
 * @property Item $frame
 * @property OrderLine $orderLine
 */
class OrderLineDetail extends _OrderLineDetail
{
	public $free_item_libelle;
	public $free_item_price_htva;
	public $free_item_vat;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for computation.
            [['free_item_price_htva', 'free_item_vat'], 'number'],
            [['free_item_libelle'], 'string', 'max' => 80],
            [['free_item_libelle', 'free_item_price_htva', 'free_item_vat'], 'safe'],
       ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'free_item_price_htva' => Yii::t('store', 'Free Item Price Htva'),
	        'free_item_libelle' => Yii::t('store', 'Free Item Libelle'),
	        'free_item_vat' => Yii::t('store', 'Free Item Vat'),
        ]);
    }

	/**
	 * creates a copy of a document object with a *copy* of all its dependent objects (orderlines, etc.)
	 *
     * @return app\models\OrderLineDetail the copy
	 */
	public function deepCopy($order_line_id) {
		$copy = new OrderLineDetail($this->attributes);
		$copy->id = null;
		$copy->order_line_id = $order_line_id;
		$copy->save();
		return $copy;
	}

	/**
	 * deleteCascade all its dependent child elements and delete the document
	 */
	public function deleteCascade() {
		$this->delete();
	}
	
    /**
     * addTasks create work to complete the order line detail for a generic item.
     *  generic item is first fetched.
	 *
	 * @param Work $work Work model for OrderLine
	 *
	 * @param OrderLine $order_line OrderLine model to which this OrderLineDetail is attached to.
	 *
	 * @param string $name reference name of item
     */
	protected function addTasks($work, $order_line, $name) {
		//Yii::trace('OrderLineDetail::addTask: '.$order_line->id);
		$item = Item::findOne(['reference' => $name]);
		if($item) {
			//Yii::trace('OrderLineDetail::addTask: 2'.$item->reference);
			$item->createTasks($work, $order_line);
		}
	}

    /**
	 * createTask create work to complete the order line details
	 * since the orderlinedetail may consist of one or more items, it will create tasks associated with each item.
	 *
	 * @param Work $work Work model for OrderLine
	 *
	 * @param OrderLine $order_line OrderLine model to which this OrderLineDetail is attached to.
     */
	public function createTask($work, $order_line) {
		//Yii::trace('OrderLineDetail::createTask: '.$order_line->id);

		if(($item = $this->getChroma()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getFinish()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getSupport()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getTirage()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getCollage()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getProtection()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getFrame()->one()) != null)			
			$item->createTasks($work, $order_line);

		if($this->corner_bool)
			$this->addTasks($work, $order_line, 'YII-CoinsArrondis');
		if($this->montage_bool)
			$this->addTasks($work, $order_line, 'YII-Montage');
		if($this->renfort_bool)
			$this->addTasks($work, $order_line, 'Renfort');
	}
	
	/**
	 * Builds a string with all enabled options and price.
	 *
	 * @param boolean $show_price Whether to add price info for each individual "option"
	 *
	 * @return string OrderLineDetail textual description
	 */
	public function getDescription($show_price = false) {
		$str = '';

		if(($item = $this->getChroma()->one()) != null)
			$str .= 'ChromaLuxe '.$item->libelle_long . ($show_price ? ' ['.$this->price_chroma.'€], '  : ', ');

		if(($item = $this->getFrame()->one()) != null)			
			$str .= 'Cadre '.$item->libelle_long . ($show_price ? ' ['.$this->price_frame.'€], '  : ', ');

		if($this->renfort_bool)
			$str .= 'Renforts' . ($show_price ? ' ['.$this->price_renfort.'€], '  : ', ');

		if($this->corner_bool)
			$str .= 'Coins arrondis' . ($show_price ? ' ['.$this->price_border.'€], '  : ', ');

		if($this->montage_bool)
			$str .= 'Montage' . ($show_price ? ' ['.$this->price_montage.'€], '  : ', ');

		if(($item = $this->getTirage()->one()) != null)
			$str .= $item->libelle_long . ($show_price ? ' ['.$this->price_tirage.'€], '  : ', ');

		if(($item = $this->getFinish()->one()) != null)
			$str .= $item->libelle_long . ($show_price ? ' ['.$this->price_finish.'€], '  : ', ');
			
		if(($item = $this->getProtection()->one()) != null)
			$str .= $item->libelle_long . ($show_price ? ' ['.$this->price_protection.'€], '  : ', ');
			
		if(($item = $this->getCollage()->one()) != null)
			$str .= $item->libelle_long . ($show_price ? ' ['.$this->price_collage.'€], '  : ', ');

		if(($item = $this->getSupport()->one()) != null)
			$str .= $item->libelle_long . ($show_price ? ' ['.$this->price_support.'€], '  : ', ');

		return trim($str, ', ');		
	}
	
}