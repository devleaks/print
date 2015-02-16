<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class DocumentLineDetail extends _DocumentLineDetail
{
	public $free_item_libelle;
	public $free_item_price_htva;
	public $free_item_vat;
	public $tirage_factor_virgule;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for computation.
			//[['unit_price'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
            [['free_item_price_htva', 'free_item_vat', 'tirage_factor_virgule'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
            [['free_item_libelle'], 'string', 'max' => 80],
            [['free_item_libelle', 'free_item_price_htva', 'free_item_vat', 'tirage_factor_virgule'], 'safe'],
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
	        'tirage_factor_virgule' => Yii::t('store', 'Tirage Factor'),
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
//		if(($item = $this->getTirage()->one()) != null) // tirage operations are created by document_line.item when it IS a tirage
//			$item->createTasks($work, $order_line);
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
		if($this->filmuv_bool)
			$this->addTasks($work, $order_line, 'UV');
	}
	
	/**
	 * Builds a string with all enabled options and price.
	 *
	 * @param boolean $show_price Whether to add price info for each individual "option"
	 *
	 * @return string DocumentLineDetail textual description
	 */
	private function price2($label, $price, $m, $p) {
		if($m == 'html') {
			$prep = '<li class="double-arrow">';
			$post = '</li>';
			$str = '<small><ul class="list-unstyled">';
			$obr = '<span style="font-size: 0.8em;"> (';
			$cbr = ')</span>';
			$eur = '€';
		} else {
			$prep = '';
			$post = ', ';
			$str = '';
			$obr = ' [';
			$cbr = ']';
			$eur = '€';
		}
		return $prep.$label.' '.($p && $price ? $obr.$price.$eur.$cbr.$post  : $post);
	}

	protected function getDescriptionMode($mode, $show_price = false) {

		$str = '';

		if(($item = $this->getChroma()->one()) != null)
			$str .= $this->price2('ChromaLuxe '.$item->libelle_long, $this->price_chroma, $mode, $show_price);

//		if(($item = $this->getTirage()->one()) != null)
//			$str .= $this->price2($item->libelle_long, $this->price_tirage);

		if(($item = $this->getFinish()->one()) != null)
			$str .= $this->price2($item->libelle_long, null, $mode, $show_price);
			

		if(($item = $this->getSupport()->one()) != null)
			$str .= $this->price2($item->libelle_long, $this->price_support, $mode, $show_price);

		if(($item = $this->getCollage()->one()) != null)
			$str .= $this->price2($item->libelle_long, $this->price_collage, $mode, $show_price);

		if(($item = $this->getProtection()->one()) != null)
			$str .= $this->price2($item->libelle_long, $this->price_protection, $mode, $show_price);
			
		if(($item = $this->getChassis()->one()) != null)
			$str .= $this->price2($item->libelle_long, $this->price_chassis, $mode, $show_price);


		if(($item = $this->getFrame()->one()) != null)			
			$str .= $this->price2('Cadre '.$item->libelle_long, $this->price_frame, $mode, $show_price);

		if($this->montage_bool)
			$str .= $this->price2('Montage', $this->price_montage, $mode, $show_price);

		if($this->renfort_bool)
			$str .= $this->price2('Renforts', $this->price_renfort, $mode, $show_price);

		if($this->filmuv_bool)
			$str .= $this->price2('Film UV', $this->price_filmuv, $mode, $show_price);

		if($this->corner_bool)
			$str .= $this->price2('Coins arrondis', null, $mode, $show_price);
		
		return ($mode == 'html' ? $str.'</ul></small>' : trim($str, ', '));		
	}
	
	public function getDescription($show_price = false) {
		return $this->getDescriptionMode('text', $show_price);
	}
	
	public function getDescriptionHTML($show_price = false) {
		return $this->getDescriptionMode('html', $show_price);
	}


	public function prepareRenfort() {
		if(!$this->renfort_bool) return;
		$ol = $this->getOrderLine()->one();
		
		// These will eventually become Parameter(s)
		$MAXDIM = 100; // cm
		$INSIDE_SMALL = 5;
		$INSIDE_LARGE = 10;
		$RENFORT_WIDTH = 1.5;
		$WIDEFRAME_ADD = 2;
		$WIDE_FRAMES = ['AmBoxAyous52910'];

		$wide_frame = false;
		if($frame = $ol->getFrame())
			$wide_frame = in_array($frame->reference, $WIDE_FRAMES);

		$largestdim   = max($ol->work_width, $ol->work_height);
		$smallesstdim = min($ol->work_width, $ol->work_height);
		$ratio = ($ol->work_width > $ol->work_height) ? $ol->work_width / $ol->work_height : $ol->work_height / $ol->work_width; // always >= 1.
		
		if($ol->isChromaLuxe()) {
			$inside = ($largestdim > $MAXDIM) ? 10 : 5;
			if($wide_frame)
				$inside += $WIDEFRAME_ADD;
				
		} else if ($support = $ol->getSupport()) {
			$inside = 5;
			if($wide_frame)
				$inside += $WIDEFRAME_ADD;
		}

		$cut = new Coupe([
			'work_length' => $largestdim - $inside,
			'quantity' => 2 * $ol->quantity,
			'document_line_id' => $ol->id,
		]);
		$cut->save();
		$cut = new Coupe([
			'work_length' => $smallesstdim - $inside - 2 * $RENFORT_WIDTH,
			'quantity' => 2 * $ol->quantity,
			'document_line_id' => $ol->id,
		]);
		$cut->save();

	}

































}