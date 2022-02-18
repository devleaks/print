<?php
/**
 * This is the model class for PDF letters.
 */

namespace app\models;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Pdf;
use kartik\mpdf\Pdf as MPdf;
use yii\base\Model;

class PDFDocument extends Model {

	/** Paper Size A4 */
	const FORMAT_A4 = 'A4';
	/** Paper Size A5 */
	const FORMAT_A5 = 'A5';

    // orientation
    const ORIENT_PORTRAIT = 'P';
    const ORIENT_LANDSCAPE = 'L';

	/** Location of common views */
	const COMMON_BASE = '@app/modules/store/prints/common/';
	/** Extension of PDF files */
	const PDF_EXT = '.pdf';

	/** Controller used to render views/PDF. */
	public $format = PDFDocument::FORMAT_A4;
	public $orientation = PDFDocument::ORIENT_PORTRAIT;

	protected $rendered = false;

	public $save = false;
	public $filename = null;
	
	public $PDF;

	public $header;
	public $footer;

	public $content;
	public $watermark;

	public $title;
	public $subject;
	public $author  = 'Colorfields';
	public $creator = 'Colorfields';
	public $keywords;

	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['format', 'orientation', 'filename', 'header', 'footer', 'content', 'watermark', 'pdf'], 'safe'],
        ];
    }


	/**
	 *	Generates mPDF data structure from model attributes
	 */
	protected function getPdfData() {
		$pdfData = [
	        // set to use core fonts only
	        'mode' => MPdf::MODE_CORE, 
	        // A4 paper format
	        'format' => $this->format, 
	        // portrait orientation
	        'orientation' => $this->orientation, 
	        // your html content input
	        'content' => $this->content,  
	        // format content from your own css file if needed or use the
	        // enhanced bootstrap css built by Krajee for mPDF formatting 
	        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
	        // any css to be embedded if required
			'cssInline' => $this->format == PDFDocument::FORMAT_A5 ?
				'.kv-wrap{padding:14px;}' .
	        	'.kv-heading-1{font-size:13px}'.
                '.kv-align-center{text-align:center;}' .
                '.kv-align-left{text-align:left;}' .
                '.kv-align-right{text-align:right;}' .
                '.kv-align-top{vertical-align:top!important;}' .
                '.kv-align-bottom{vertical-align:bottom!important;}' .
                '.kv-align-middle{vertical-align:middle!important;}' .
                '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-caption{font-size:1.1em;padding:6px;border:1px solid #ddd;border-bottom:none;}' .
				'table.no-break tr td,table.no-break tr th{page-break-inside: avoid;}' .
                'table{font-size:0.8em;}'
				:
				'.kv-wrap{padding:20px;}' .
	        	'.kv-heading-1{font-size:18px}'.
                '.kv-align-center{text-align:center;}' .
                '.kv-align-left{text-align:left;}' .
                '.kv-align-right{text-align:right;}' .
                '.kv-align-top{vertical-align:top!important;}' .
                '.kv-align-bottom{vertical-align:bottom!important;}' .
                '.kv-align-middle{vertical-align:middle!important;}' .
                '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}' .
				'table.no-break tr td,table.no-break tr th{page-break-inside: avoid;}' .
                'table{font-size:0.8em;}'
				,
	         // set mPDF properties on the fly
			'marginHeader'	=> $this->format == PDFDocument::FORMAT_A5 ?  5 : 10,
			'marginFooter'	=> $this->format == PDFDocument::FORMAT_A5 ?  5 : 10,
			'marginTop'		=> $this->format == PDFDocument::FORMAT_A5 ? 30 : 35,
			'marginBottom'	=> $this->format == PDFDocument::FORMAT_A5 ? 25 : 35,
			'marginLeft'	=> $this->format == PDFDocument::FORMAT_A5 ?  5 : 15,
			'marginRight'	=> $this->format == PDFDocument::FORMAT_A5 ?  5 : 15,
			'methods'		=> [],
			'options'		=> [],
		];
				
		if($this->header)	$pdfData['methods']['SetHTMLHeader'] = $this->header;
		if($this->footer) 	$pdfData['methods']['SetHTMLFooter'] = $this->footer;
		if($this->title)	$pdfData['methods']['SetTitle']      = ( $this->title ? $this->title : $this->subject );
		if($this->author)	$pdfData['methods']['SetAuthor']     = $this->author;
		if($this->creator)	$pdfData['methods']['SetCreator']    = $this->creator;
		if($this->subject)	$pdfData['methods']['SetSubject']    = ( $this->subject ? $this->subject : $this->title );
		if($this->keywords)	$pdfData['methods']['SetKeywords']   = $this->keywords.'colorfields.be colorfields photo chromaluxe fine arts printing frame';

		if($this->watermark) {
			$pdfData['options']['showWatermarkText'] = true;
			$pdfData['methods']['SetWatermarkText'] = $this->watermark;
		}

		//Yii::trace('save?='.$this->save, 'PDFDocument::getPdfData');		
		if($this->save) {
			$this->generateFilename();
			$pdfData['destination'] = MPdf::DEST_FILE;
			$fn = RuntimeDirectoryManager::getDocumentRoot().$this->filename;
			$pdfData['filename'] = $fn;
			Yii::trace('pdfDate[filename]='.$fn, 'PDFDocument::getPdfData');		
		} else {
			$pdfData['destination'] = MPdf::DEST_BROWSER;
		}
		return $pdfData;
	}
	
	
    /**
     * Generates filename for saving from document type.
     */
	public function generateFilename($name = null) {
		if($this->save)
			$this->filename = RuntimeDirectoryManager::getFilename(RuntimeDirectoryManager::DOCUMENT, $name);
	}


    /**
     * Saves PDF.
     */
	public function save() {
		//Yii::trace('fn='.$this->filename, 'PDFDocument::save');
		if($this->filename) {
			$this->deletePrevious();
			$pdf = new Pdf([
				'document_type' => $this->destination,
				'filename' => $this->filename,
			]);
			return $pdf->save();
		}
	}

	public function getFile() {
		return Pdf::findOne(['filename' => $this->filename]);
	}

	public function deletePrevious() {
		if( $existing = Pdf::findOne(['filename' => $this->filename]) )
			$existing->delete();	
	}

	/*
	 *	Renders document
	 */	
	public function render() {
    	$this->PDF = new MPdf($this->getPdfData());
		Yii::trace('rendering'.$this->PDF->filename, 'PDFDocument::render');
		$pdf = $this->PDF->render();
		$this->rendered = true;
		//Yii::trace('saved'.$this->PDF->filename, 'PDFDocument::render');
		$this->save();
		return $this->filename ? $this->filename : $pdf;
	}

}