<?php

namespace app\components;

use Yii;
use app\models\Account;
use app\models\Bill;
use app\models\Client;
use app\models\CoverLetter;
use app\models\Credit;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;

class PdfDocumentGenerator extends PDFDocument {
	
	public function __construct ($c) {
		$this->controller = $c;
	}

	/**
	 *	A4 negative account statement.
	 *
	 *	@param integer $client_id Identifier of client for which we generate statement.
	 *	@param boolean $send Whether to send document to client after generation, and if client email is available.
	 */
	public function accountExtract($client_id, $send = false) {

		$client = Client::findOne($client_id);

		// 1. Shows history from latest payment or oldest unpaid bill, which ever is oldest.
		$latest_unpaid_accnt_line = Account::find()
			->andWhere(['client_id' => $client->id])
			->andWhere(['status' => Account::TYPE_DEBIT])
			->orderBy('created_at asc')
			->one();
		if(!$latest_unpaid_accnt_line) {
			echo 'Impossible: Account '.$client_id.' is negative and no unpaid bill found. Please check account manually.';
			return;
		}
		$latest = $latest_unpaid_accnt_line->created_at;

		$latest_deposit = Account::find()
			->andWhere(['client_id' => $client->id])
			->andWhere(['status' => Account::TYPE_CREDIT])
			->orderBy('created_at desc')
			->one();

		if($latest_deposit)
			$latest = $latest_deposit->created_at < $latest_unpaid_accnt_line->created_at ? $latest_deposit->created_at : $latest_unpaid_accnt_line->created_at;
		
		// 2. build table for cover letter
		$viewBase = '@app/modules/store/prints/account/';
	    $table = $this->controller->renderPartial($viewBase.'extract_lines', [
			'to_date' => $latest,
			'lines'  => Account::find()
									->andWhere(['client_id' => $client->id])
									->andWhere(['>=', 'created_at', $latest])
									->orderBy('created_at asc'),
			'opening_balance' => Account::getBalance($client->id, $latest),
			'closing_balance' => Account::getBalance($client->id),
			'dataProvider' => new ActiveDataProvider([
				'query' => Account::find()
									->andWhere(['client_id' => $client->id])
									->andWhere(['>=', 'created_at', $latest])
									->orderBy('created_at asc')
			])
		]); // '<table><tr><td>Test Table</td></tr></table>';

		// 3. build cover letter
		$coverLetter = new CoverLetter([
			'type'		=> 'ACCOUNT_UNBALANCED',
			'client'	=> $client,			
			'date'		=> date('d/m/Y', strtotime('now')),			
			'subject'	=> Yii::t('store', 'Your account statement.'),			
			'body'		=> Yii::t('store', 'Please read attached document(s).'),			
			'table'		=> $table, 			
			'watermark'	=> false,			
			'viewBase'	=> null, // standard cover letter
			'destBase'	=> RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_ACCOUNT_SLIP),
			'controller'=> $this->controller,
		]);
		
		$coverLetter->render();

		if($send)
			$coverLetter->send();
	}


	/**
	 *	A4 late bill cover letter (level 0..3)
	 *
	 *	@param integer $client_id Identifier of client for which we generate statement.
	 *	@param Document[] $bills Array of Document of type TYPE_BILL.
	 *	@param Attachment[] $docs Array of Attachment containing PDF of above bills.
	 *	@param boolean $send Whether to send document to client after generation, and if client email is available.
	 */
	public function lateBills($client_id, $bills, $docs, $send = false) {
		$subjects = [
			Yii::t('store', 'Unpaid Bills'),
			Yii::t('store', 'Late Unpaid Bills'),
			Yii::t('store', 'Late Unpaid Bills - 2nd Reminder'),
			Yii::t('store', 'Late Unpaid Bills - Last Reminder'),
		];

		// number of months late, 0..3(max)
		$days = floor( (time() - strtotime($bills[0]->created_at)) / (60*60*24) );
		$type = floor($days / 30);
		if($type > 3) $type = 3;

		// table for standard cover letter
		$viewBase = '@app/modules/store/prints/late-bills/';
	    $table = $this->controller->renderPartial($viewBase.'late-bills', ['bills' => $bills]); // '<table><tr><td>Test Table</td></tr></table>'

		$coverLetter = new CoverLetter([
			'type'		=> 'LATE_BILL_COVER'.$type,
			'client'	=> Client::findOne($client_id),			
			'date'		=> date('d/m/Y', strtotime('now')),			
			'subject'	=> $subjects[$type],			
			'body'		=> Yii::t('store', 'Please read attached document(s).'),
			'table'		=> $table, 			
			'watermark' => false,			
			'viewBase' 	=> null,			
			'destBase'	=> RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_LATE_BILLS),
			'controller'=> $this->controller,
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
	 *	A4 document generation; saves the document in a file. File name is returned upon completion.
	 *
	 *	@param Document $document Document to generate file for.
	 *	@param string $dirName Path to folder where to generate file.
	 *	@param string $viewBase Path to folder where to locate views for generation.
	 *	@param string $watermark String to use for watermark accross document pages.
	 *
	 *	@return string Full path to generated document or null.
	 */
	public function document($document, $dirName, $viewBase, $watermark = '') {
		$name = $document->client->sanitizeName();
		$pathroot = $dirName.$name;
		$documentName = $document->name.'.pdf';
		$documentPath = $pathroot.'-'.$documentName;

		$viewBase = '@app/modules/store/prints/common/';
	    $header  = $this->controller->renderPartial($viewBase.'header', ['model' => $document]);
	    $footer  = $this->controller->renderPartial($viewBase.'footer', ['model' => $document]);

		$viewBase = '@app/modules/store/prints/document/';
	    $content = $this->controller->renderPartial($viewBase.'body', ['model' => $document]);

		$pdfData = [
	        // set to use core fonts only
	        'mode' => Pdf::MODE_CORE, 
	        // A4 paper format
	        'format' => Pdf::FORMAT_A4, 
	        // portrait orientation
	        'orientation' => Pdf::ORIENT_PORTRAIT, 
	        // stream to browser inline
	        'destination' => Pdf::DEST_FILE,
	 		'filename' => $documentPath,
	        // your html content input
	        'content' => $content,  
	        // format content from your own css file if needed or use the
	        // enhanced bootstrap css built by Krajee for mPDF formatting 
	        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
	        // any css to be embedded if required
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
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}' .
                'table{font-size:0.8em;}'
				,
	         // set mPDF properties on the fly
			'marginHeader' => 10,
			'marginFooter' => 10,
			'marginTop' => 35,
			'marginBottom' => 35,
			'options' => [
				'showWatermarkText' => true,
			],
	         // call mPDF methods on the fly
	        'methods' => [ 
	        //    'SetHeader'=>['Laboratoire JJ Micheli'], 
	            'SetHTMLHeader'=> $header,
	            'SetHTMLFooter'=> $footer,
				'SetWatermarkText'=> $watermark,
	        ]
		];

    	$pdf = new Pdf($pdfData);
		$pdf->render();
		return $documentPath;
	}

}