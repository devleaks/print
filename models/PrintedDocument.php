<?php
/**
* This is the model class to generate standard printed document for bid, bills, order, vouchers
* File is saved if destBase is supplied.
*
*/
namespace app\models;
use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Pdf;
class PrintedDocument extends PDFLetter {
	const SEP = '-';
	const IMAGES = 'I';
	const TABLE_IMAGES = 'T';
	const ANNEX_IMAGES = 'A';
	
	public $document;
	/** include images in doc, if available */
	public $images = false;
	
	public $images_loc = self::TABLE_IMAGES;
	public $viewBase;
	
	/** Example:
			$printedDocument = new PrintedDocument([
					'format'	=> PDFDocument::FORMAT_A4,
					'document'	=> $document,
					'watermark'	=> $watermark,
				'save' => true|false, // default false
			]);
			$result = $printedDocument->render();
	*/
/**
* @inheritdoc
*/
public function rules()
{
return array_merge(parent::rules(), [
[['document', 'viewBase'], 'safe'],
]);
}
/**
* @inheritdoc
*/
	public function generateFilename($name = null) {
		if($this->save)
			$this->filename = RuntimeDirectoryManager::getFilename(RuntimeDirectoryManager::DOCUMENT, $name, $this->document);
	}
/**
* @inheritdoc
*/
	public function save() {
		if($this->filename) {
			$this->deletePrevious();
			$pdf = new Pdf([
				'document_type' => RuntimeDirectoryManager::DOCUMENT,
				'document_id' => $this->document->id,
				'filename' => $this->filename,
			]);
			return $pdf->save();
		}
	}
/**
* @inheritdoc
*/
	public function render() {
		$lang_before = Yii::$app->language;
		Yii::$app->language = $this->language;
		$this->title = $this->document ? Yii::t('print', ($this->document->document_type == Document::TYPE_ORDER && $this->document->bom_bool) ? Document::TYPE_BOM : $this->document->document_type).' '.$this->document->name : '';
		$vb = $this->viewBase ? $this->viewBase : '@app/modules/store/prints/document/';
	$this->content = Yii::$app->controller->renderPartial($vb.'body', ['model' => $this->document, 'images' => $this->images ? $this->images_loc : false]);
		Yii::$app->language = $lang_before;
		return parent::render();
	}
	/**
		*	Send this document to client if email address is available. Do nothing otherwise.
	*/
	public function send($subject, $body, $email = null) {
		if(!$this->rendered)
			$this->render();
		if(!$this->filename)
			return;
			
		if($file = $this->getFile())
			$file->send($subject, $body, $email);
	}
}