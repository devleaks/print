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
	/** */
	const IMAGE_SIZE_FACTOR = 1.5;

	/** */
	const EXTRA_REBATE_FIRST = 'REBATE_FIRST';
	/** */
	const EXTRA_REBATE_ACCESS = 'REBATE_ACCESS';
	/** */
	const EXTRA_REBATE_AMOUNT = 'REBATE_AMOUNT';
	/** */
	const EXTRA_REBATE_PERCENTAGE = 'REBATE_PERCENTAGE';
	/** */
	const EXTRA_SUPPLEMENT_AMOUNT = 'SUPPLEMENT_AMOUNT';
	/** */
	const EXTRA_SUPPLEMENT_PERCENTAGE = 'SUPPLEMENT_PERCENT';

	const SEPARATOR = '###';

    public $image;
	public $image_add;
	public $final_htva;
	public $final_tvac;

	public $quantity_virgule;
	public $unit_price_virgule;
	public $vat_virgule;
	public $extra_amount_virgule;

	public $work_width_virgule;
	public $work_height_virgule;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			// added for computation.
            [['final_htva', 'final_tvac'], 'number'],

			// added for pattern masking
			[['quantity_virgule', 'unit_price_virgule', 'vat_virgule', 'extra_amount_virgule', 'work_width_virgule', 'work_height_virgule'],
				'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],

            // added for file upload.
            [['image', 'image_add'], 'safe'],
            [['image'], 'file', 'maxFiles' => self::MAX_IMAGES, 'extensions' => 'jpg, jpeg, png, gif', 'mimeTypes' => 'image/jpeg, image/png, image/gif',],
       ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'quantity_virgule' => Yii::t('store', 'Quantity'),

	        'unit_price_virgule' => Yii::t('store', 'Unit Price'),
	        'vat_virgule' => Yii::t('store', 'Vat'),

	        'extra_amount_virgule' => Yii::t('store', 'Extra Amount'),

	        'final_htva' => Yii::t('store', 'Total Htva'),
	        'final_tvac' => Yii::t('store', 'Total TVAC'),

            'work_width_virgule' => Yii::t('store', 'Work Width'),
            'work_height_virgule' => Yii::t('store', 'Work Height'),

	        'image' => Yii::t('store', 'Images'),
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

		foreach($this->getWorkLines()->each() as $old)
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
	public function generateFilename($filename = '') {
		return substr( $this->document->due_date , 0, 7 ) . DIRECTORY_SEPARATOR
				//	 . $this->document_id . DIRECTORY_SEPARATOR // not necessary
                     . $this->id . DIRECTORY_SEPARATOR
					 . $filename;
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


	protected function getMainPrice() {
		if($detail = $this->getDetail())
			return ($this->item_id == Item::TYPE_CHROMALUXE) ? $detail->price_chroma : $detail->price_tirage;
		return 0;
	}

	protected function getAccessoryPrice() {
		return $this->price_htva - $this->getDetailMainPrice();
	}

	/**
	 * update price of document line
	 * set the value value of document-line level rebate/supplement line if any.
	 */
	public function updatePrice() {
		$item = $this->getItem()->one();
		if($item->reference != Item::TYPE_REBATE) { // global rebate line, work is not done here...
			if(! $item->hasPriceComputation()) // otherwise, price is computed and set before calling this proc.
				$this->unit_price = $item->prix_de_vente;

			$this->vat = $item->taux_de_tva;
			// this line regular amount without rebate/supplement
			$this->price_htva = $this->quantity * $this->unit_price;
			$this->price_tvac = $this->price_htva * (1 + ($this->vat / 100));
			// extra amount (signed)
			if(isset($this->extra_type) && ($this->extra_type != '')) {
				if(isset($this->extra_amount) && ($this->extra_amount > 0)) {
					if($this->extra_type == self::EXTRA_REBATE_FIRST || $this->extra_type == self::EXTRA_REBATE_ACCESS) {
						$percent = $this->extra_amount / 100;
						$item_price = ($this->extra_type == self::EXTRA_REBATE_FIRST) ? $this->getMainPrice() : $this->getAccessoryPrice();
						$this->extra_htva = round( - $item_price * $percent, 2 ); // always a rebate
					} else { // rebate on entire DL amount
						$amount = strpos($this->extra_type, "PERCENT") > -1 ? $this->price_htva * ($this->extra_amount/100) : $this->extra_amount;
						//Yii::trace('amount='.$amount, 'DocumentLine::updatePrice');
						$asigne = strpos($this->extra_type, "SUPPLEMENT_") > -1 ? 1 : -1;
						$this->extra_htva = round( $asigne * $amount, 2 );
					}
					//Yii::trace('htva='.$this->extra_htva, 'DocumentLine::updatePrice');
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
	 *
	 */
	public function getPlaceholder() {
		$fact = self::IMAGE_SIZE_FACTOR;
		return ($this->work_width > 0 && $this->work_height > 0) ?
			'<div class="image-placeholder" style="width: '.($fact*$this->work_width).'px; height: '.($fact*$this->work_height).'px;">'.$this->work_width.'&times;'.$this->work_height.'</div>'
			:
			''
			;
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
	public function getDescription($show_price = true) {

		if($this->item->reference == Item::TYPE_REBATE) {
			$str = strpos($this->extra_type, "SUPPLEMENT_") > -1 ? Yii::t('store', 'Supplement') : Yii::t('store', 'Rebate');
			$str .= ' ('.$this->getExtraDescription().')';
			return $str;
		}

		$misc_label = $this->note;
		$misc_note  = '';
		if(($pos = strpos($this->note, self::SEPARATOR)) !== false) {
			$misc_label = substr($this->note, 0, $pos);
			$misc_note  = substr($this->note, $pos + strlen(self::SEPARATOR));
		}

		$str = ($this->item->reference == Item::TYPE_MISC) ? $misc_label : $this->item->libelle_long;

		if($this->work_width > 0 && $this->work_height > 0)
			$str .= ' '.$this->work_width.'×'.$this->work_height;

		if($detail = $this->getDetail()) {
			if($show_price && $this->isTirage(true))
				$str .= ' <small>('.$detail->price_tirage.'<span style="font-size: 0.8em;">€</span>)</small>';

			$str .= $detail->getDescriptionHTML($show_price); // $str .= ' ('.$detail->getDescription($show_price).')';
		}

		if($this->item->reference != Item::TYPE_MISC && $this->note != '') // for free text item, comment IS the label
			$str .= '<br/><small class="rednote"><span style="text-decoration: underline;">Note</span>: '.$this->note.'</small>';
		else if($this->item->reference == Item::TYPE_MISC && $misc_note != '')
			$str .= '<br/><small class="rednote"><span style="text-decoration: underline;">Note</span>: '.$misc_note.'</small>';

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


	public function generateLabels() {
		$viewBase = '@app/modules/store/prints/label/';

		if($pics_array = $this->getPictures()->all())
			$pics_count = count($pics_array);
		else
			$pics_count = 0;

		$content = '';
		for($i=0; $i<$this->quantity; $i++) {
			if($i > 0)
				$content .= '<pagebreak />';

		    $content .= Yii::$app->controller->renderPartial($viewBase.'item-label', [
				'model' => $this,
				'sequence' => $i + 1,
				'picture' => $i < $pics_count ? $pics_array[$i] : null,
			]);
		}

		$pdf = new PDFLabel([
			'content' => $content
		]);
		return $pdf->render();
	}

	public static function getHeightCount($item_id, $min, $max) {
		return self::find()->andWhere(['item_id' => $item_id])
						->andWhere(['>=',  'work_height', $min])
						->andWhere(['<', 'work_height', $max])
						->sum('quantity');
	}

	public static function getWidthCount($item_id, $min,$max) {
		return self::find()->andWhere(['item_id' => $item_id])
						->andWhere(['>=',  'work_width', $min])
						->andWhere(['<', 'work_width', $max])
						->sum('quantity');
	}

	public static function getDetailHeightCount($what, $item_id, $min, $max) {
		return self::find()->joinWith('documentLineDetails')
						->andWhere(['document_line_detail.'.$what.'_id' => $item_id])
						->andWhere(['>=',  'work_height', $min])
						->andWhere(['<', 'work_height', $max])
						->sum('quantity');
	}

	public static function getDetailWidthCount($what, $item_id, $min,$max) {
		return self::find()->joinWith('documentLineDetails')
						->andWhere(['document_line_detail.'.$what.'_id' => $item_id])
						->andWhere(['>=',  'work_width', $min])
						->andWhere(['<', 'work_width', $max])
						->sum('quantity');
	}

	public function isChromaLuxe() {
		$item = Item::findOne($this->item_id);
		return $item->yii_category == 'ChromaLuxe';
	}

	public function isTirage($canvasToo = false) {
		$item = Item::findOne($this->item_id);
		if($canvasToo)
			return $item->yii_category == 'Tirage' || $item->yii_category == 'Canvas';
		else
			return $item->yii_category == 'Tirage';
	}

	public function getSupport() {
		if( $old = $this->getDetail() )
			return $old->support_id ? Item::findOne($old->support_id) : null;
		return null;
	}

	public function getFrame() {
		if( $old = $this->getDetail() )
			return $old->frame_id ? Item::findOne($old->frame_id) : null;
		return null;
	}
}
