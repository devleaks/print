<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\Bill;
use app\models\BillSearch;
use app\models\Client;
use app\models\Order;
use app\models\OrderSearch;
use app\models\Payment;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;

/**
 * BillController implements the CRUD actions for Bill model.
 */
class BillController extends Controller
{
    public function behaviors()
    {
        return [
	        'access' => [
	            'class' => 'yii\filters\AccessControl',
	            'ruleConfig' => [
	                'class' => 'app\components\AccessRule'
	            ],
	            'rules' => [
	                [
	                    'allow' => false,
	                    'roles' => ['?']
               		],
					[
	                    'allow' => true,
	                    'roles' => ['admin', 'compta'],
	                ],
	            ],
	        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['!=','document.status',Bill::STATUS_CLOSED]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Bill models.
     * @return mixed
     */
    public function actionClientUnpaid($id)
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['!=','document.status',Bill::STATUS_CLOSED])
							->andWhere(['client_id' => $id]);

        return $this->render('client', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Bill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	protected function actionClientAccount($id) {
		Yii::trace($id, 'BillController::actionClientAccount');
		if( $model = Bill::findOne($id) ) {
			$model->addPayment($model->getBalance(), Payment::TYPE_ACCOUNT);
		}
	}

	protected function actionSendRemider($id) {
		Yii::trace($id, 'BillController::actionSendRemider');
	}

	protected function actionUpdateStatus($id, $status) {
		Yii::trace($status, 'BillController::actionUpdateStatus');
		$model = $this->findModel($id);
		$model->setStatus($status);
	}
	
	
	public function actionBoms() {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['document.bom_bool' => true, 'document.status' => [Order::STATUS_DONE, Order::STATUS_TOPAY]]);

        return $this->render('boms', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	public function actionBillBoms() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {
					Yii::trace('Count:'.count($_POST['selection']), 'BillController::actionBillBoms');
					$q = Order::find()->where(['document.id' => $_POST['selection']])->select('client_id')->distinct();
					$bills = [];
					foreach($q->each() as $client) {
						$docs = [];
						foreach(Order::find()->where(['document.id' => $_POST['selection'], 'client_id' => $client->client_id])->each() as $doc)
							$docs[] = $doc->id;
						$bills[] = Bill::createFromBoms($docs);
						Yii::trace('client:'.$client->client_id.', bill='.$bills[count($bills)-1]->id, 'BillController::actionBillBoms');
					}
			        $dataProvider = new ArrayDataProvider([
						'allModels' => $bills,
					]);
			        return $this->render('bom-bills', [
			            'dataProvider' => $dataProvider,
			        ]);
				}
			}

		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	
	protected function generateCover($client, $level, $filename, $docs) {
	    $header  = $this->renderPartial('_print_header', ['model' => $client]);
	    $content = $this->renderPartial('_print', ['model' => $client, 'level' => $level, 'documents' => $docs]);
	    $footer  = $this->renderPartial('_print_footer', ['model' => $client]);

		$pdfData = [
	        // set to use core fonts only
	        'mode' => Pdf::MODE_CORE, 
	        // A4 paper format
	        'format' => Pdf::FORMAT_A4, 
	        // portrait orientation
	        'orientation' => Pdf::ORIENT_PORTRAIT, 
	        // stream to browser inline
	        'destination' => Pdf::DEST_BROWSER, 
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
			'options' => [],
	         // call mPDF methods on the fly
	        'methods' => [ 
	        //    'SetHeader'=>['Laboratoire JJ Micheli'], 
	            'SetHTMLHeader'=> $header,
	            'SetHTMLFooter'=> $footer,
	        ]
		];

		if($filename) {
			$pdfData['destination'] = Pdf::DEST_FILE;
			$pdfData['filename'] = $filename;
		} else {
			$pdfData['destination'] = Pdf::DEST_BROWSER;
		}

    	$pdf = new Pdf($pdfData);
		return $pdf->render();
	}


	public function actionBulkAction() {
		$send = false;
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {
					if(isset($_POST['action'])) {
						Yii::trace($_POST['action'], 'BillController::actionBulkAction');
						$action = $_POST['action'];
						if(in_array($action, [Bill::ACTION_PAYMENT_RECEIVED, Bill::ACTION_SEND_REMINDER, Bill::ACTION_CLIENT_ACCOUNT])) {
							if($action == Bill::ACTION_PAYMENT_RECEIVED) {
								
								foreach($_POST['selection'] as $id) {
									$this->actionUpdateStatus($id, Bill::STATUS_CLOSED);
								}
								Yii::$app->session->setFlash('success', Yii::t('store', 'Document(s) status updated.'));

							} else if ($action == Bill::ACTION_CLIENT_ACCOUNT) {

								foreach($_POST['selection'] as $id) {
									$this->actionClientAccount($id);
								}
								Yii::$app->session->setFlash('success', Yii::t('store', 'Bill(s) transferred to client account(s).'));

							} else { // ACTION_SEND_REMINDER, loop per client
								
								$dirname = Yii::getAlias('@runtime').'/document/late-bills/';
								if(!is_dir($dirname))
								    if(!mkdir($dirname, 0777, true)) {
										Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot create directory for extraction.'));
										return $this->redirect(Yii::$app->request->referrer);
								    }

								$q = Bill::find()->where(['document.id' => $_POST['selection']])->select('client_id')->distinct();
								foreach($q->each() as $b) {
									$client = Client::findOne($b->client_id);
									$docs = [];
									$bills = [];
									$late_degree = 0;
									$name = $client->sanitizeName();
									$pathroot = $dirname.($client->email != '' ? Bill::EMAIL_PREFIX : '').$name;
									foreach(Bill::find()->where(['document.id' => $_POST['selection'], 'client_id' => $client->id])->each() as $bill) {
										// 1. generate PDFs for each late bill:
										$billname = $bill->name.'.pdf';
										$billpath = $pathroot.'-'.$billname;										
										$this_degree = $bill->getDelay(true);
										if($this_degree > $late_degree) $late_degree = $this_degree;
										$bill->generatePdf($this, $billpath);
										$docs[] = ['path' => $billpath, 'name' => $billname];
										$bills[] = $bill;
									}
									// 2. Generate cover depending on degree
									$cover_filename = $pathroot.'cover-'.$late_degree.'.pdf';
									$this->generateCover($client, $late_degree, $cover_filename, $bills);

									// 3. Send bills by email if possible
									if($send && $client->email != '') {
										$mail = Yii::$app->mailer->compose()
										    ->setFrom( Yii::$app->params['fromEmail'] )
										    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $client->email )  // <======= FORCE DEV EMAIL TO TEST ADDRESS
										    ->setSubject(Yii::t('store', 'Late bills'))
											->setTextBody(Yii::t('store', 'Please find attached your late bills.'))
											->attach($fullpath, ['fileName' => 'letter.pdf', 'contentType' => 'application/pdf']);
										foreach($docs as $doc)
											$mail->attach($doc['path'], ['fileName' => $doc['name'], 'contentType' => 'application/pdf']);
										$mail->send();
									}
								}

								Yii::$app->session->setFlash('warning', Yii::t('store', 'Reminders sent and/or ready to print.'));
							}
						}
					}
					return $this->redirect(Yii::$app->request->referrer);
				}
			}

		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Yii::$app->request->referrer);
	}
	
}
