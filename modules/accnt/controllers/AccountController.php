<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Bill;
use app\models\CaptureBalance;
use app\models\Client;
use app\models\ClientSearch;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionClient($id)
    {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');
		
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['client_id' => $id]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'client' => $client
        ]);
    }


	public function actionIndex() {
		$query = new Query();
		$query->from('account');
		
        $dataProvider = new ActiveDataProvider([
			'query' => $query->select(['client_id, sum(amount) as tot_amount'])->groupBy('client_id'),
		]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
	}

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionBalance($id)
    {
		$capture = new CaptureBalance();
		$capture->client_id = $id;

        if ($capture->load(Yii::$app->request->post())) {
			// 1. Do we have bills checked?
			if(isset($_POST)) {
				if(isset($_POST['selection'])) {
					if(count($_POST['selection']) > 0) {
						$connection = \Yii::$app->db;
						$transaction = $connection->beginTransaction();
						// record payment
						$available = str_replace(',','.',$capture->amount);
						$payment_ok = true;
						$note = '';
						foreach(Account::find()->where(['id' => $_POST['selection']])->each() as $accnt_line) {
							Yii::trace('Left='.$available, 'AccountController::actionBalance');
							if( ($available - abs($accnt_line->amount)) > -0.009 ) { // had enough money to pay; rounding needed to the 0.001
								if($accnt_line->document_id) {
									$bill = Bill::findDocument($accnt_line->document_id);
									$note .= $bill->name.', ';
								} else
									$note .= $accnt_line->amount.'€, ';
									
								$accnt_line->status = Account::TYPE_BALANCED;
								$accnt_line->save();
								$available -= abs(round($accnt_line->amount,2));
								Yii::trace('Using='.abs(round($accnt_line->amount,2)), 'AccountController::actionBalance');
							} else { // no enough money to pay account
								Yii::trace('Tryied to use='.abs(round($accnt_line->amount,2)).', only '.
										$available.' left. ('.($available - abs($accnt_line->amount)).')', 'AccountController::actionBalance');
								$payment_ok = false;
								Yii::$app->session->setFlash('warning', 'Amount is not sufficient to pay account line(s).');
							}
						}
						if($payment_ok) {
							$note = trim($note, ', ');
							$payment = new Account([
								'amount' => str_replace(',','.',$capture->amount),
								'status' => 'ACREDIT',
								'client_id' => $capture->client_id,
								'note' => substr($note, 0, 158),
								'payment_method' => $capture->method,
							]);
							$payment->save();
							$transaction->commit();
							$capture = new CaptureBalance();
							Yii::$app->session->setFlash('success', 'Account line(s) were sucessfully balanced.');
						} else {
							$transaction->rollback();
						}
					} else {
						Yii::$app->session->setFlash('danger', 'You did not check any bill.');
					}
				} else {
					Yii::$app->session->setFlash('danger', 'You did not check any bill.');
				}
			}

			
		}
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');
	
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['client_id' => $id]);
        return $this->render('balance', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'client' => $client,
			'capture' => $capture,
        ]);
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');
		
        $model = new Account();
		$model->client_id = $client->id;
		$model->status = Account::TYPE_CREDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['client', 'id' => $client->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['client', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$client_id = $model->client_id;
		$model->delete();
        return $this->redirect(['client', 'id' => $client_id]);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/**
	 * G E N E R A T E   D O C U M E N T S
	 */

	protected function generatePdf($client, $filename) {
	    $header  = $this->renderPartial('_print_header', ['model' => $client]);
	    $content = $this->renderPartial('_print', ['model' => $client]);
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

	public function actionBulkNotify() {
		$send = false;
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {
					$dirname = Yii::getAlias('@runtime').'/document/account/'.date('Y-m-d').'/';
					if(!is_dir($dirname))
					    if(!mkdir($dirname, 0777, true)) {
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot create directory for account document.'));
							return $this->redirect(Yii::$app->request->referrer);
					    }
					foreach(Client::find()->where(['id' => $_POST['selection']])->each() as $client) {
						$fn = $client->sanitizeName();
						$filename = ($client->email != '') ? Bill::EMAIL_PREFIX.$fn : $fn;
						$fullpath = $dirname.$filename.'.pdf';
						$this->generatePdf($client, $fullpath);
						if($send && $client->email != '') {
							Yii::$app->mailer->compose()
							    ->setFrom( Yii::$app->params['fromEmail'] )
							    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $client->email )  // <======= FORCE DEV EMAIL TO TEST ADDRESS
							    ->setSubject(Yii::t('store', 'Your client account'))
								->setTextBody(Yii::t('store', 'Please find attached your client account statement.'))
								->attach($fullpath, ['fileName' => $fn.'.pdf', 'contentType' => 'application/pdf'])
							    ->send();
						}
					}
					Yii::$app->session->setFlash('success', Yii::t('store', 'Mail(s) sent').'.');
					return $this->redirect(Url::to(['account/index']));
				}
			}
		}
		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Url::to(['account/index']));
	}

}
