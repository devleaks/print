<?php
/**
 * This is the model class to generate standard "cover letter" for "complains" (late bills, negative account extracts...).
 * Format is A4, always.
 *
 */

namespace app\models;

use Yii;
use app\components\PdfDocumentGenerator;
use kartik\mpdf\Pdf;

class CoverLetter extends PDFDocument {
	const SEP = '-';
	
	public $client;

	public $type;
	public $date;
	public $subject;
	public $body;
	public $table;

	public $watermark;
	
	public $filename;
	public $pdf;
	
	public $viewBase;
	public $destBase;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller', 'client', 'type', 'date', 'subject', 'body', 'table', 'watermark', 'filename', 'pdf', 'viewBase', 'destBase'], 'safe'],
        ];
    }

	/* Generate unique cover letter name
	 *
	 */
	protected function generateFilename() {
		return $this->filename = $this->destBase.$this->type.self::SEP.$this->client->sanitizeName().self::PDF_EXT;
	}


	/* Renders cover letter
	 *
	 */
	public function render() {
	    $header  = $this->controller->renderPartial(self::COMMON_BASE.'header', ['format' => PdfDocumentGenerator::FORMAT_A4, 'language' => $this->client->lang]);
	    $footer  = $this->controller->renderPartial(self::COMMON_BASE.'footer', ['format' => PdfDocumentGenerator::FORMAT_A4, 'language' => $this->client->lang]);
	
		$viewBase = $this->viewBase ? $this->viewBase : self::COMMON_BASE;
		
		$content = $this->controller->renderPartial($this->viewBase.'cover-letter',  ['model' => $this]);
		
		$this->generateFilename();
		
		$pdfData = [
	        'mode' => Pdf::MODE_CORE, 
	        'format' => Pdf::FORMAT_A4, 
	        'orientation' => Pdf::ORIENT_PORTRAIT, 
	        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
			'cssInline' => '.kv-wrap{padding:20px;}' .
	        	'.kv-heading-1{font-size:18px}'.
                '.kv-align-center{text-align:center;}' .
                '.kv-align-left{text-align:left;}' .
                '.kv-align-right{text-align:right;}' .
                '.kv-align-top{vertical-align:top!important;}' .
                '.kv-align-bottom{vertical-align:bottom!important;}' .
                '.kv-align-middle{vertical-align:middle!important;}' .
                '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
			'marginHeader' => 10,
			'marginFooter' => 10,
			'marginTop' => 35,
			'marginBottom' => 35,
			'options' => [],
	        'methods' => [ 
	            'SetHTMLHeader'=> $header,
	            'SetHTMLFooter'=> $footer,
	        ],
	        'content' => $content,  
		];

		if($this->watermark) {
			$pdfData['options']['showWatermarkText'] = true;
			$pdfData['methods']['SetWatermarkText'] = $this->watermark;
		}

		if($this->filename) {
			$pdfData['destination'] = Pdf::DEST_FILE;
			$pdfData['filename'] = $this->filename;
		} else {
			$pdfData['destination'] = Pdf::DEST_BROWSER;
		}

    	$this->pdf = new Pdf($pdfData);
		echo 'CoverLetter:'.$this->filename;
		return $this->pdf->render();
	}

	/* Send cover letter to client if email address is available. Do nothing otherwise.
	 *
	 */
	public function send() {
		if($this->client->email != '') {
			$mail = Yii::$app->mailer->compose()
				->setFrom( Yii::$app->params['fromEmail'] )
				->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $this->client->email )
				->setSubject($this->subject)
				->setTextBody($this->body)
				->attach($this->filename, ['fileName' => basename($this->filename), 'contentType' => 'application/pdf'])
				->send();
		}
	}


	/* Send cover letter to client if email address is available. Do nothing otherwise.
	 *
	 *	@param app\models\Attachment[] $docs Documents (files) to attach.
	 */
	public function sendWithAttachments($docs) {
		if($this->client->email != '') {
			$mail = Yii::$app->mailer->compose()
				->setFrom( Yii::$app->params['fromEmail'] )
				->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $this->client->email )
				->setSubject($this->subject)
				->setTextBody($this->body)
				->attach($this->filename, ['fileName' => basename($this->filename), 'contentType' => 'application/pdf']);
			foreach($docs as $doc)
				$mail->attach($doc->filename, ['fileName' => $doc->title, 'contentType' => $doc->mimetype ? $doc->mimetype : 'application/pdf']);
			$mail->send();
		}
	}
}
