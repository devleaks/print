<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class DocumentLineDetail extends _DocumentLineDetail
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
			//[['unit_price'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
            [['free_item_price_htva', 'free_item_vat'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
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
	 * creates a copy of a document object with a *copy* of all its dependent objects (documentlinelines, etc.)
	 *
     * @return app\models\DocumentLineDetail the copy
	 */
	public function deepCopy($document_line_id) {
		$copy = new DocumentLineDetail($this->attributes);
		$copy->id = null;
		$copy->document_line_id = $document_line_id;
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
	 * @param Work $work Work model for DocumentLine
	 *
	 * @param DocumentLine $order_line DocumentLine model to which this DocumentLineDetail is attached to.
	 *
	 * @param string $name reference name of item
     */
	protected function addTasks($work, $order_line, $name) {
		//Yii::trace('1:'.$order_line->id, 'DocumentLineDetail::addTask');
		$item = Item::findOne(['reference' => $name]);
		if($item) {
			//Yii::trace('2:'.$item->reference, 'DocumentLineDetail::addTask');
			$item->createTasks($work, $order_line);
		}
	}

    /**
	 * createTask create work to complete the order line details
	 * since the documentlinedetail may consist of one or more items, it will create tasks associated with each item.
	 *
	 * @param Work $work Work model for DocumentLine
	 *
	 * @param DocumentLine $order_line DocumentLine model to which this DocumentLineDetail is attached to.
     */
	public function createTask($work, $order_line) {
		//Yii::trace($order_line->id, 'DocumentLineDetail::createTask');

		if(($item = $this->getChroma()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getTirage()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getFinish()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getSupport()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getCollage()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getProtection()->one()) != null)
			$item->createTasks($work, $order_line);
		if(($item = $this->getFrame()->one()) != null)			
			$item->createTasks($work, $order_line);
		if(($item = $this->getChassis()->one()) != null)			
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
	 * @return string DocumentLineDetail textual description
	 */
	protected function getDescriptionMode($mode, $show_price = false) {
		if($mode == 'html') {
			$prep = '<li>';
			$post = '</li>';
			$str = '<small><ul class="list-unstyled">';
			$obr = ' (';
			$cbr = ')';
		} else {
			$prep = '';
			$post = ', ';
			$str = '';
			$obr = ' [';
			$cbr = ']';
		}

		if(($item = $this->getChroma()->one()) != null)
			$str .= $prep.'ChromaLuxe '.$item->libelle_long . ($show_price ? $obr.$this->price_chroma.'€'.$cbr.$post  : $post);

		if(($item = $this->getTirage()->one()) != null)
			$str .= $prep.$item->libelle_long . ($show_price ? $obr.$this->price_tirage.'€'.$cbr.$post  : $post);

		if(($item = $this->getFinish()->one()) != null)
			$str .= $prep.$item->libelle_long /*. ($show_price ? $obr.$this->price_finish.'€'.$cbr.$post  : ', ')*/. $post;
			

		if(($item = $this->getSupport()->one()) != null)
			$str .= $prep.$item->libelle_long . ($show_price ? $obr.$this->price_support.'€'.$cbr.$post  : $post);

		if(($item = $this->getCollage()->one()) != null)
			$str .= $prep.$item->libelle_long . ($show_price ? $obr.$this->price_collage.'€'.$cbr.$post  : $post);

		if(($item = $this->getProtection()->one()) != null)
			$str .= $prep.$item->libelle_long . ($show_price ? $obr.$this->price_protection.'€'.$cbr.$post  : $post);
			
		if(($item = $this->getChassis()->one()) != null)
			$str .= $prep.$item->libelle_long . ($show_price ? $obr.$this->price_chassis.'€'.$cbr.$post  : $post);


		if(($item = $this->getFrame()->one()) != null)			
			$str .= $prep.'Cadre '.$item->libelle_long . ($show_price ? $obr.$this->price_frame.'€'.$cbr.$post  : $post);

		if($this->montage_bool)
			$str .= $prep.'Montage' . ($show_price ? $obr.$this->price_montage.'€'.$cbr.$post  : $post);

		if($this->renfort_bool)
			$str .= $prep.'Renforts' . ($show_price ? $obr.$this->price_renfort.'€'.$cbr.$post  : $post);

		if($this->corner_bool)
			$str .= $prep.'Coins arrondis' /*. ($show_price ? $obr.$this->price_border.'€'.$cbr.$post  : ', ')*/. $post;

			
		return ($mode == 'html' ? $str.'</ul></small>' : trim($str, ', '));		
	}
	
	public function getDescription($show_price = false) {
		return $this->getDescriptionMode('text', $show_price);
	}
	
	public function getDescriptionHTML($show_price = false) {
		return $this->getDescriptionMode('html', $show_price);
	}

}