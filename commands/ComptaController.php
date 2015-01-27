<?php

namespace app\commands;

use app\components\RuntimeDirectoryManager;
use app\components\PdfDocumentGenerator;
use app\models\Attachment;
use app\models\Bill;
use app\models\Credit;
use app\models\Client;
use app\models\Order;
use app\models\Payment;
use kartik\mpdf\Pdf;
use yii\console\Controller;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use Yii;

class ComptaController extends Controller {
	protected $report;
	
	protected function report($s, $p = null) {
		$str = $p ? $p.': '.$s : $s;
		if(!isset($this->report)) $this->report = [];
		$this->report[] = $str;
		echo $str.'
';
	}


	protected function getReport($reset = false) {
		$body = '';
		if(!isset($this->report))
			$body = Yii::t('store', 'Nothing to report.');
		else
			foreach($this->report as $s)
				$body .= $s;
				
		if($reset) $this->report = [];

		return $body;
	}


	protected function notify_admin($subject) {
		Yii::$app->mailer->compose()
		    ->setFrom( Yii::$app->params['fromEmail'] )
		    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : Yii::$app->params['adminEmail'] )
		    ->setSubject( $subject )
			->setTextBody( $this->getReport(true) )
		    ->send();
		echo 'mail sent: '.$subject;
	}
	

	protected function email($client, $subject, $body, $attachments) {
		if($client->email != '') {
			$mail = Yii::$app->mailer->compose()
			    ->setFrom( Yii::$app->params['fromEmail'] )
			    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $client->email )
			    ->setSubject($subject)
				->setTextBody($body);
			foreach($docs as $doc)
				$mail->attach($attachments['path'], ['fileName' => $attachments['name'], 'contentType' => 'application/pdf']);
			$mail->send();
		}
	}


    public function actionLateBills() {
		echo "Starting ComptaController::actionLateBills ..";
		// collect late bills:
		$late = date('Y-m-d', strtotime('1 months ago'));
		$q =  Bill::find()
					->andWhere(['!=','document.status',Bill::STATUS_CLOSED])
					->andWhere(['<=','created_at',$late])
					->orderBy('client_id, created_at asc'); // latest bill first
		// loop over clients:
		$clg = new PdfDocumentGenerator($this);
		$dirName = RuntimeDirectoryManager::getDirectory(RuntimeDirectoryManager::LATE_BILLS);
		$viewBase = '@app/modules/accnt/views/bill/';

		$watermarks = [
			Yii::t('store', 'Duplicate'),
			Yii::t('store', 'Reminder'),
			Yii::t('store', '2nd Reminder'),
			Yii::t('store', 'Last Reminder'),
		];

		$client_id = -1;
		$bills = [];
		$docs  = [];
		foreach($q->each() as $bill) {
			if($client_id == -1) $client_id = $bill->client_id;

			// $fn = $this->generateBill($bill);
			$days = floor( (time() - strtotime($bill->created_at)) / (60*60*24) );
			$type = floor($days / 30);
			if($type > 3) $type = 3;
			$fn = $clg->document($bill, $dirName, $viewBase, $watermarks[$type]);
			$docs[] = new Attachment(['filename' => $fn, 'title' => $bill->name]);
			$bills[] = $bill;
			echo 'lateBill '.$fn.'..';

			// generate cover for previous
			if($bill->client_id != $client_id) {
				echo 'generateCover for '.$client_id.'..';
				$clg->lateBills($bill->client_id, $bills, $docs, true);
				$bills= [];
				$docs = [];
				$client_id = $bill->client_id;
			}
		}
		// generate cover for last
		if($client_id != -1) {
			echo 'generateCover for '.$client_id.'..';
			$clg->lateBills($client_id, $bills, $docs, true);
		}

 		echo ". done.\r\n";
    }


    public function actionDailyBalance() {
		echo "Starting ComptaController::actionDailyBalance ..";
		$viewBase = '@app/modules/accnt/views/payment/';
		$yesterday = date('Y-m-d', strtotime('yesterday'));

		$dirname = RuntimeDirectoryManager::getDirectory(RuntimeDirectoryManager::DAILY_REPORT);
		$filename = $dirname.'daily-'.$yesterday.'.pdf';
		$day_start = $yesterday. ' 00:00:00';
		$day_end   = $yesterday. ' 23:59:59';

		$content = '';

		// SUMMARY
		$query = new Query();
		  $query->select(['payment_method, count(id) as tot_count, sum(amount) as tot_amount'])
				->from('payment')
				->andWhere(['>=','created_at',$day_start])
				->andWhere(['<=','created_at',$day_end])
				->groupBy(['payment_method'])
		;
		$content .= $this->renderPartial($viewBase.'_summary_pdf', ['totals' => $query]);
		
		// DETAILS, per payment method:
		foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
			$query = Payment::find()
						->andWhere(['>=','created_at',$day_start])
						->andWhere(['<=','created_at',$day_end])
						->andWhere(['payment_method' => $payment_method]);
			$content .= $this->renderPartial($payment_method == Payment::TYPE_ACCOUNT ? $viewBase.'_detail-account_pdf' : $viewBase.'_detail_pdf',
					['query' => $query, 'method' => $payment_label]);
		}
		
		$pdfData = [
	        // set to use core fonts only
	        'mode' => Pdf::MODE_CORE, 
	        // A4 paper format
	        'format' => Pdf::FORMAT_A4, 
	        // portrait orientation
	        'orientation' => Pdf::ORIENT_PORTRAIT, 
	        // stream to browser inline
	        'destination' => Pdf::DEST_FILE,
			'filename' => $filename,
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
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
	         // set mPDF properties on the fly
			'marginHeader' => 10,
			'marginFooter' => 10,
			'options' => [],
		];

    	$pdf = new Pdf($pdfData);
		$pdf->render();

		echo ". done.\r\n";
    }


	/**
	 * Generates transfer file for current month
	 */
    public function actionPopsyTransfer() {
		echo "Starting ComptaController::actionPopsyTransfer ..";
		$month_ini = new \DateTime("first day of this month");
		$month_end = new \DateTime("last day of this month");
		$date_from = $month_ini->format('Y-m-d');
		$date_to   = $month_end->format('Y-m-d');
		
		$dirname = RuntimeDirectoryManager::getDirectory(RuntimeDirectoryManager::EXTRACTION);

		// CREDITS
		$docs = Credit::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<=','created_at',$date_to]);

		$viewBase = '@app/modules/accnt/views/extraction/';
        $extraction = $this->renderPartial($viewBase.'_extract', [
            'models' => $docs,
        ]);

		$filename = 'popsi-credits-'.date('Y-m-d');
		file_put_contents($dirname.$filename.'.txt', $extraction);
							
		// BILLS
		$docs = Bill::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<=','created_at',$date_to]);
        $extraction = $this->renderPartial($viewBase.'_extract', [
            'models' => $docs,
        ]);

		$filename = 'popsi-bills-'.date('Y-m-d');
		file_put_contents($dirname.$filename.'.txt', $extraction);
							
		echo $dirname.$filename.".txt . done.\r\n";
    }


    public function actionBillsFromBoms() {
		echo "Starting ComptaController::actionBillsFromBoms ..";
        $q = Order::find()->andWhere(['document.bom_bool' => true, 'document.status' => [Order::STATUS_DONE, Order::STATUS_TOPAY]])->select('client_id')->distinct();
		$bills = [];
		foreach($q->each() as $client) {
			$docs = [];
			$this->report(Yii::t('store', 'Billing of Bills of Materials for {client}', ['client' => $client->id]));

			foreach(Order::find()->andWhere(['document.bom_bool' => true])
								 ->andWhere(['document.status' => [Order::STATUS_DONE, Order::STATUS_TOPAY]])
								 ->andWhere(['client_id' => $client->client_id])
								 ->each() as $doc) {
				$docs[] = $doc->id;
				$this->report(Yii::t('store', 'Added of Bills of Materials {bom}.', ['bom' => $doc->name]));
			}
	
			$bills[] = Bill::createFromBoms($docs);
			$this->report(Yii::t('store', 'Bill {bill}', ['bill' => $bills[count($bills)-1]->name]));
			echo 'client:'.$client->client_id.', bill='.$bills[count($bills)-1]->id;
		}
		$this->notify_admin(Yii::t('store', 'Billing of Bills of Materials'));
		echo ". done.\r\n";
    }


	public function actionTestId($test) {
		echo Client::getUniqueIdentifier($test);
	}
}