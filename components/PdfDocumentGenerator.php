<?php

namespace app\components;

use Yii;
use app\models\Bill;
use app\models\Client;
use app\models\CoverLetter;
use app\models\PrintedDocument;
use app\models\PDFLabel;
use app\models\PDFLetter;
use app\models\Credit;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;

class PdfDocumentGenerator {
	/**
	 *	A4 late bill cover letter (level 0..3)
	 *
	 *	@param integer $client_id Identifier of client for which we generate statement.
	 *	@param Document[] $bills Array of Document of type TYPE_BILL.
	 *	@param Attachment[] $docs Array of Attachment containing PDF of above bills.
	 *	@param boolean $send Whether to send document to client after generation, and if client email is available.
	 */
	public function lateBills($client_id, $bills, $docs, $send = false, $exttype = null) {
		$subjects = [
			Yii::t('store', 'Unpaid Bills'),
			Yii::t('store', 'Late Unpaid Bills'),
			Yii::t('store', 'Late Unpaid Bills - 2nd Reminder'),
			Yii::t('store', 'Late Unpaid Bills - Last Reminder'),
		];

		// number of months late, 0..3(max)
		$type = 0;
		if($exttype === null) {
			$days = floor( (time() - strtotime($bills[0]->created_at)) / (60*60*24) );
			$type = floor($days / 30);
		} else {
			$type = intval($exttype);
		}
		if($type > 3) $type = 3;

		// table for standard cover letter
		$viewBase = '@app/modules/store/prints/late-bills/';
	    $table = Yii::$app->controller->renderPartial($viewBase.'late-bills', ['bills' => $bills]); // '<table><tr><td>Test Table</td></tr></table>'

		$coverLetter = new CoverLetter([
			'type'		=> 'LATE_BILL_COVER'.$type,
			'client'	=> Client::findOne($client_id),			
			'date'		=> date('d/m/Y', strtotime('now')),			
			'subject'	=> $subjects[$type],			
			'body'		=> Yii::t('store', 'Please read attached document(s).'),
			'table'		=> $table, 			
			'watermark' => false,			
			'viewBase' 	=> null,			
			'save'		=> true,
			'destination' => RuntimeDirectoryManager::LATE_BILLS,
		]);
		
		$coverLetter->render();		

		if($send) {
			if(count($docs)>0)
				$coverLetter->sendWithAttachments($docs);
			else
				$coverLetter->send();
		}		
	}


	/**
	 *	Generic document generation. Optionnally saves the document in a file, in which case file name is returned upon completion; otherwise PDF document is returned.
	 *
	 *	@param Document $document Document to generate file for.
	 *	@param string $dirName Path to folder where to generate file.
	 *	@param string $viewBase Path to folder where to locate views for generation.
	 *	@param string $watermark String to use for watermark accross document pages.
	 *
	 *	@return string Full path to generated document or null.
	 */
	public function document($document, $dirName, $viewBase, $watermark = '') {
		$pdf = new PrintedDocument([
			'document'		=> $document,
			'watermark'		=> $watermark,
			'save'			=> $dirName != null,
		]);
		return $pdf->render();
	}

	/**
	 *	Print labels always in A5 format. Always returns PDF stream, never generate files.
	 *
	 *	@param string $content Labels
	 *	@return PDF PDF of labels
	 */
	public function labels($labels) {
		$pdf = new PDFLabel([
			'content' => $labels
		]);		
		return $pdf->render();
	}

}