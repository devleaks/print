<?php
/**
 * This is the model class to generate standard "cover letter" for "complains" (late bills, negative account extracts...).
 * Format is A4, always. File is always saved, because most of the time, it is attached to an email, or ready to print.
 *
 */

namespace app\models;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Pdf;

class CoverLetter extends PDFLetter {
	const SEP = '-';
	
	/** Types of cover letter */
	const ACCOUNT_UNBALANCED = 'ACCOUNT_UNBALANCED';
	const LATE_BILL_COVER0 = 'LATE_BILL_COVER0';
	const LATE_BILL_COVER1 = 'LATE_BILL_COVER1';
	const LATE_BILL_COVER2 = 'LATE_BILL_COVER2';
	const LATE_BILL_COVER3 = 'LATE_BILL_COVER3';
	
	public $client;

	public $type;
	public $date;
	public $subject;
	public $body;
	public $table;
	
	public $viewBase;

	/** Example:
			$pdfDocument = new PDFDocument([
				'format'	=> PDFDocument::FORMAT_A4,
				'orientation'=> PDFDocument::ORIENT_PORTRAIT,
				'filename'	=> $filename,
				'header'	=> $header,
				'footer'	=> $footer,
				'content'	=> $content,
				'watermark'	=> $watermark,
			]);
			$result = $pdfDocument->render();
	*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['client', 'type', 'date', 'subject', 'body', 'table', 'viewBase', 'destBase'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
	public function generateFilename($name = null) {
		if($this->save)
			$this->filename = RuntimeDirectoryManager::getFilename($this->destination, $this->type, null, $this->client);
	}

    /**
     * @inheritdoc
     */
	public function render() {
		$viewBase = $this->viewBase ? $this->viewBase : self::COMMON_BASE;		
		$this->content = Yii::$app->controller->renderPartial($viewBase.'cover-letter',  ['model' => $this]);
		return parent::render();
	}


    /**
     * @inheritdoc
     */
	public function save() {
		if($this->filename) {
			$this->deletePrevious();
			$pdf = new Pdf([
				'document_type' => $this->type,
				'client_id' => $this->client->id,
				'filename' => $this->filename,
			]);
			return $pdf->save();
		}
	}


	/* Send cover letter to client if email address is available. Do nothing otherwise.
	 *
	 */
	public function send() {
		if($file = $this->getFile()) {
			$file->send($this->subject, $this->body, $this->client->email);
		}
	}


	/* Send cover letter to client if email address is available. Do nothing otherwise.
	 *
	 *	@param app\models\Attachment[] $docs Documents (files) to attach.
	 */
	public function sendWithAttachments($docs) {
		if($this->client->email != '') {
			try {
				$mail = Yii::$app->mailer->compose()
					->setFrom( Yii::$app->params['fromEmail'] )
					->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $this->client->email )
					->setSubject($this->subject)
					->setTextBody($this->body)
					->attach(RuntimeDirectoryManager::getDocumentRoot().$this->filename, ['fileName' => basename($this->filename), 'contentType' => 'application/pdf']);

				$replyTo = YII_ENV_DEV ?
										( isset(Yii::$app->params['testEmail']) ? Yii::$app->params['testEmail'] : null )
										:
										( isset(Yii::$app->params['replyToAltEmail']) ?
											Yii::$app->params['replyToAltEmail']
											:
											( isset(Yii::$app->params['replyToEmail']) ? Yii::$app->params['replyToEmail'] : null )
										);

				if($replyTo)
					$mail->setReplyTo($replyTo);

				foreach($docs as $doc) {
					$mail->attach(RuntimeDirectoryManager::getDocumentRoot().$doc->filename, ['fileName' => $doc->title, 'contentType' => $doc->mimetype ? $doc->mimetype : 'application/pdf']);
				}
				$mail->send();
				// timestamp mailed docs
				if($file = $this->getFile()) $file->sent();
				foreach($docs as $doc)
					if( $file = Pdf::findOne(['filename' => $doc->filename]) )
						$file->sent();
				Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
			} catch (Swift_TransportException $STe) {
				Yii::error($STe->getMessage(), 'Pdf::send::ste');
				Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
			} catch (Exception $e) {
				Yii::error($e->getMessage(), 'Pdf::send::e');				
				Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
			}
		}
	}
}
