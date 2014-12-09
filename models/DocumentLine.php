<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_line".
 *
 * @property integer $id
 * @property integer $document_id
 * @property integer $position
 * @property double $quantity
 * @property double $price
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 * @property double $work_width
 * @property double $work_height
 * @property string $note
 *
 * @property LineItem[] $lineItems
 * @property OrderItem[] $orderItems
 * @property Order $order
 * @property Picture[] $pictures
 */
class DocumentLine extends _DocumentLine
{
	/** Maximum number of images with one order line */
	const MAX_IMAGES = 20;	
	/** Image verbs */
	const IMAGE_ADD = 'ADD';	
	/** */
	const IMAGE_REPLACE = 'REPLACE';

    public $image;
	public $image_add;
	public $final_htva;
	public $final_tvac;

	public $quantity_virgule;
	public $unit_price_virgule;
	public $vat_virgule;
	public $extra_amount_virgule;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for computation.
            [['final_htva', 'final_tvac'], 'number'],
			[['quantity_virgule', 'unit_price', 'vat_virgule', 'extra_amount_virgule'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],

            // added for file upload.
            [['image', 'image_add'], 'safe'],
            [['image'], 'file', 'maxFiles' => self::MAX_IMAGES, 'extensions' => 'jpg, png, gif', 'mimeTypes' => 'image/jpeg, image/png, image/gif',],
       ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'final_htva' => Yii::t('store', 'Total Htva'),
	        'final_tvac' => Yii::t('store', 'Total Tvac'),
	        'image' => Yii::t('store', 'Image'),
        ]);
    }


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

    /**
     * @inheritdoc
     */
	public function deepCopy($document_id) {
		$copy = new DocumentLine($this->attributes);
		$copy->id = null;
		$copy->document_id = $document_id;
		$copy->save();
		foreach($this->getDocumentLineDetails()->each() as $sub)
			$sub->deepCopy($copy->id);
		foreach($this->getPictures()->each() as $pic)
			$pic->deepCopy($copy->id);
		return $copy;
	}
		
    /**
     * @inheritdoc
     */
	public function deleteCascade() {
		foreach($this->getDocumentLineDetails()->each() as $old)
			$old->deleteCascade();

		$this->deletePictures();

		$this->delete();
	}
	
	/**
	 * Delete all pictures associated with DocumentLine.
	 */
	public function deletePictures() {
		foreach($this->getPictures()->each() as $pic)
			$pic->deleteCascade();
	}
	
    /**
     * @param string $filename
	 *
     * @return string Partial picture path for DocumentLine. Based on DocumentLine id, and Order date.
     */
	public function getFileName($filename = '') {
		$parent = $this->getDocument()->one();
		$year = $parent ? substr( $parent->due_date, 0, 7 ) : '2000-00';
		return $year . DIRECTORY_SEPARATOR
//					 . $this->document_id . DIRECTORY_SEPARATOR
                     . $this->id . DIRECTORY_SEPARATOR
					 . $filename;
	}

    /**
     * @param string $filename
	 *
     * @return string Path to folder where all pictures are stored.
     */
	public function getPicturePath($filename = '') {
		return Yii::$app->params['picturePath'] . $this->getFileName($filename);
	}

    /**
	 * createWork create work to complete the order line, loops and create tasks for order line detail if any
	 *
     * @param $work Work model to attach WorkLine models to.
	 *
     * @return app\models\Work
     */
	public function createTask($work) {
		$this->item->createTasks($work, $this);		
		foreach($this->getDocumentLineDetails()->each() as $old) {	// there should only be one...
			$old->createTask($work, $this);
		}
	}
	

	/**
	 * update price of document line
	 * set the value value of document-line level rebate/supplement line if any.
	 */
	public function updatePrice() {
		$item = $this->getItem()->one();
		if($item->reference != Item::TYPE_REBATE) { // global rebate line, work is not done here...
			if(! $item->hasPriceComputation()) // otherwise, price is computed and set before calling this proc.
				$this->unit_price = $item->unit_price;

			$this->vat = $item->vat;
			// this line regular cost
			$this->price_htva = $this->quantity * $this->unit_price;
			$this->price_tvac = $this->price_htva * (1 + ($this->vat / 100));
			// extra cost
			if(isset($this->extra_type) && ($this->extra_type > 0)) {
				if(isset($this->extra_amount) && ($this->extra_amount > 0)) {
					$amount = strpos($this->extra_type, "PERCENT") > -1 ? $this->price_htva * ($this->extra_amount/100) : $this->extra_amount;
					$asigne = strpos($this->extra_type, "SUPPLEMENT_") > -1 ? 1 : -1;
					$this->extra_htva = round( $asigne * $amount, 2 );
				}
			}
		} // else, ignore global rebate line
	}
	
	/**
	 * @return boolean whether the order line has picture attached to it
	 */
	public function hasPicture() {
		return $this->getPictures()->count() > 0;
	}
	
	/**
	 * @return boolean Whether DocumentLine model has DocumentLineDetail model associated with it.
	 */
	public function hasDetail() {
		return $this->getDocumentLineDetails()->count() > 0;
	}
	
	/**
	 * @return DocumentLineDetail DocumentLineDetail model associated with it or null.
	 */
	public function getDetail() {
		return $this->getDocumentLineDetails()->one();
	}
	
	/**
	 * @return string Description of DocumentLine, together with description of DocumentLineDetail if any.
	 */
	public function getDescription() {
		$str = ($this->item->reference == '#') ? $this->note : $this->item->libelle_long;
		if($this->work_width > 0 && $this->work_height > 0)
			$str .= ' '.$this->work_width.'×'.$this->work_height;
		
		if($detail = $this->getDetail())
			$str .= ' ('.$detail->getDescription(true).')';

		if($this->item->reference != '#' && $this->note != '') // for free text item, comment IS the label
			$str .= ' ('.$this->note.')';
		
		return $str;
	}

	/**
	 * @return string Localized description of rebate or supplement associated with DocumentLine.
	 */
	public function getExtraDescription($show_amount = false) {
		$extra = '';
		if(isset($this->extra_type) && ($this->extra_type != '')) {
			if(isset($this->extra_amount) && ($this->extra_amount > 0)) {
				$extra = (strpos($this->extra_type, "SUPPLEMENT_") > -1 ? '+' : '-')
						. $this->extra_amount
						. (strpos($this->extra_type, "PERCENT") > -1 ? '%' : '€')
						;
				if($show_amount)
					$extra .= ' = '.$this->extra_htva.'€';
			}
		}
		return $extra;
	}
	
}
