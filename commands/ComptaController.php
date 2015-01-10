<?php

namespace app\commands;

use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\Bill;
use app\models\Credit;
use app\models\Client;
use app\models\Order;
use app\models\Payment;
use app\models\CoverLetter;
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


	protected function generateCover($client_id, $bills) {
		echo 'generateCover for '.$client_id.'..';

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

		$viewBase = '@app/modules/accnt/views/bill/';
	    $table = $this->renderPartial($viewBase.'_late-bills', ['bills' => $bills]); // '<table><tr><td>Test Table</td></tr></table>'

		$coverLetter = new CoverLetter([
			'type' => 'LATE_BILL_COVER'.$type,
			'client' => Client::findOne($client_id),			
			'date' => date('d/m/Y', strtotime('now')),			
			'subject' => $subjects[$type],			
			'body' => Yii::t('store', 'Please read attached document.'),			
			'table' => $table, 			
			'watermark' => false,			
			'viewBase' => '@app/modules/store/views/print/',			
			'destBase' => RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_LATE_BILLS),
			'controller' => $this,
		]);
		
		$coverLetter->render();
	}


	protected function generateBill($bill) {
		$days = floor( (time() - strtotime($bill->created_at)) / (60*60*24) );
		$type = floor($days / 30);
		if($type > 3) $type = 3;
		
		$dirname = RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_LATE_BILLS);
		$name = $bill->client->sanitizeName();
		$pathroot = $dirname.$name;
		$billname = $bill->name.'.pdf';
		$billpath = $pathroot.'-'.$billname;
		echo $billpath;

		$viewBase = '@app/modules/accnt/views/bill/';
	    $header  = $this->renderPartial($viewBase.'_print_header', ['model' => $bill]);
	    $content = $this->renderPartial($viewBase.'_print_bill', ['model' => $bill]);
	    $footer  = $this->renderPartial($viewBase.'_print_footer', ['model' => $bill]);

		$pdfData = [
	        // set to use core fonts only
	        'mode' => Pdf::MODE_CORE, 
	        // A4 paper format
	        'format' => Pdf::FORMAT_A4, 
	        // portrait orientation
	        'orientation' => Pdf::ORIENT_PORTRAIT, 
	        // stream to browser inline
	        'destination' => Pdf::DEST_FILE,
	 		'filename' => $billpath,
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
				'SetWatermarkText'=>Yii::t('store', 'Reminder Type '.$type),
	        ]
		];

    	$pdf = new Pdf($pdfData);
		$pdf->render();

		echo 'generateBill for '.$bill->id.'..';
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
		$client_id = -1;
		foreach($q->each() as $bill) {
			if($client_id == -1) $client_id = $bill->client_id;

			$this->generateBill($bill);
			$bills[] = $bill;

			// generate cover for previous
			if($bill->client_id != $client_id) {
				$this->generateCover($bill->client_id, $bills, $type);
				$bills= [];
				$client_id = $bill->client_id;
			}
		}
		// generate cover for last
		if($client_id != -1) $this->generateCover($client_id, $bills);
 		echo ". done.\r\n";
    }


	protected function generateExtract($client_id) {
		echo 'generateExtract for '.$client_id.'..';
		$client = Client::findOne($client_id);

		// we show from latest payment or oldest unpaid, which ever is oldest.
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
		
		$viewBase = '@app/modules/accnt/views/account/';
	    $table = $this->renderPartial($viewBase.'_print_extract_lines', [
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

		$coverLetter = new CoverLetter([
			'type' => 'ACCOUNT_UNBALANCED',
			'client' => $client,			
			'date' => date('d/m/Y', strtotime('now')),			
			'subject' => Yii::t('store', 'Your account statement.'),			
			'body' => Yii::t('store', 'Please read attached document.'),			
			'table' => $table, 			
			'watermark' => false,			
			'viewBase' => '@app/modules/store/views/print/',			
			'destBase' => RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_ACCOUNT_SLIP),
			'controller' => $this,
		]);
		
		$coverLetter->render();
	}


    public function actionClientAccounts() {
		echo "Starting ComptaController::actionClientAccounts ..";
		Yii::trace('Starting', 'ComptaController::actionClientAccounts');
		// clients with negative account
		$query = new Query();
		$query->from('account')
			  ->select(['client_id, sum(amount) as tot_amount'])
			  ->groupBy('client_id')
			  ->having(['<', 'sum(amount)', 0]);

		foreach($query->each() as $negaccount)
			$this->generateExtract($negaccount['client_id']);

		echo ". done.\r\n";
    }


    public function actionDailyBalance() {
		echo "Starting ComptaController::actionDailyBalance ..";
		$viewBase = '@app/modules/accnt/views/payment/';
		$yesterday = date('Y-m-d', strtotime('yesterday'));

		$dirname = RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_DAILY_REPORT);
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
		$viewBase = '@app/modules/accnt/views/extraction/';
		$month_ini = new \DateTime("first day of this month");
		$month_end = new \DateTime("last day of this month");
		$date_from = $month_ini->format('Y-m-d');
		$date_to   = $month_end->format('Y-m-d');
		
		$dirname = RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_EXTRACTION);

		// CREDITS
		$docs = Credit::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<=','created_at',$date_to]);
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
							
		echo ". done.\r\n";
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

}