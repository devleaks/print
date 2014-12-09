<?php

namespace app\modules\order\controllers;

use Yii;
use app\models\Bid;
use app\models\BidSearch;
use app\models\Bill;
use app\models\BillSearch;
use app\models\CaptureEmail;
use app\models\CapturePayment;
use app\models\Client;
use app\models\Credit;
use app\models\CreditSearch;
use app\models\Document;
use app\models\DocumentLine;
use app\models\DocumentSearch;
use app\models\Item;
use app\models\Order;
use app\models\OrderSearch;
use app\models\Parameter;
use app\models\Payment;
use app\models\Sequence;
use app\models\Ticket;
use app\models\TicketSearch;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class DocumentController extends Controller
{
    public function behaviors() {
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
	                    'roles' => ['admin', 'manager', 'compta'],
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Order models for supplied type.
     * @return mixed
     */
	private function actionIndexByType($searchModel, $type) {
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('doc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'document_type' => $type,
        ]);
	}

    public function actionBids() {
        return $this->actionIndexByType(new BidSearch(), Document::TYPE_BID);
    }

    public function actionOrders() {
        return $this->actionIndexByType(new OrderSearch(), Document::TYPE_ORDER);
    }

    public function actionBills() {
        return $this->actionIndexByType(new BillSearch(), Document::TYPE_BILL);
    }

    public function actionCredits() {
        return $this->actionIndexByType(new CreditSearch(), Document::TYPE_CREDIT);
    }

    public function actionTickets() {
        return $this->actionIndexByType(new TicketSearch(), Document::TYPE_TICKET);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
		$model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            ; //@setflash?
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionSearch($search = null) {
		if($search == null)
			if(isset($_POST['search']))
				$search = $_POST['search'];

        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if($search)
			$dataProvider->query
				->with('client')
				->orWhere(['like', 'document.name', $search])
				->orWhere(['like', 'document.reference', $search])
				->orWhere(['like', 'document.reference_client', $search])
				->orWhere(['like', 'client.nom', $search])
				->orWhere(['like', 'client.autre_nom', $search])
				->orderBy('updated_by desc');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Order model and redirect to create a first line.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null, $type = Document::TYPE_ORDER) {
        $model = new Document();

		if($id !== null) $model->client_id = intval($id);

        if ($model->load(Yii::$app->request->post())) { // we just create the order
			if(!isset($model->document_type)) $model->document_type = $type;

			if(!isset($model->name)) {
				switch($model->document_type) {
					case Document::TYPE_ORDER:
					case Document::TYPE_BILL:
						$model->name = substr($model->due_date,0,4).'-'.Sequence::nextval('order_number');
						break;
					case Document::TYPE_CREDIT:
						$model->name = substr($model->due_date,0,4).'-'.Sequence::nextval('credit_number');
						break;
					default:
						$o = Parameter::getTextValue('application', $model->document_type, '-YII-');
						$model->name = substr($model->due_date,0,4).$o.Sequence::nextval('doc_number');
						break;
				}
			}
			// temporaily set reference
			$model->reference = $model->name;
			if ($model->save()) {
				$model->status = Document::STATUS_OPEN;
				$model->save();
				if($model->document_type == Document::TYPE_CREDIT) {
					$credit_item = Item::findOne(['reference' => Item::TYPE_CREDIT]);
					$model_line = new DocumentLine([
						'document_id' => $model->id,
						'item_id' => $credit_item->id,
						'quantity' => 1,
						'unit_price' => 0,
						'vat' => $credit_item->taux_de_tva,
						'due_date' => $model->due_date,
					]);
					$model_line->save();
					return $this->redirect(['document-line/update', 'id' => $model_line->id]);
				} else
					$addDocumentLine = DocumentLineController::addFirstLine($model);
				$model->updatePrice();
				return $this->redirect(['document-line/create', 'id' => $model->id]);
			}
        } else {
			if(!isset($model->document_type)) $model->document_type = $type;
			if(!isset($model->created_by)) $model->created_by = Yii::$app->user->id;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateBid($id = null) {
        return $this->actionCreate($id, Document::TYPE_BID);
    }

    public function actionCreateOrder($id = null) {
        return $this->actionCreate($id, Document::TYPE_ORDER);
    }

    public function actionCreateBill($id = null) {
        return $this->actionCreate($id, Document::TYPE_BILL);
    }

    public function actionCreateCredit($id = null) {
        return $this->actionCreate($id, Document::TYPE_CREDIT);
    }

    public function actionCreateTicket($id = null) {
		$cli = Client::findOne(['nom' => 'Client au comptoir']);
		if($cli) $id = $cli->id;
        return $this->actionCreate($id, Document::TYPE_TICKET);
    }

    public function actionCreateDoc($id = null) {
		return $this->render('create_doc');
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        return $this->redirect(['document-line/create', 'id' => $model->id]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLiveUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('info', Yii::t('store', '{document} updated', ['document' => Yii::t('store', $model->document_type)]).'.');
        }
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the referrer page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
		$model = $this->findModel($id);
		$cnt = $model->getDocuments()->count();
		if($cnt > 0)
			Yii::$app->session->setFlash('error', Yii::t('store', 'This order cannot be deleted because a child document depends on it.'));
		else
        	$this->findModel($id)->deleteCascade();

		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Document::findDocument($id)) !== null) {
			return $model;
		}
        throw new NotFoundHttpException('The requested page does not exist.');
    }

	public function actionClientList($search = null, $id = null, $ret = null) {
	    $out = ['more' => false];
	    if (!is_null($search)) {
	        $query = new Query;
	        $query->select('id, nom AS text')
	            ->from('client')
	            ->orWhere(['like', 'nom', $search])
	            ->orWhere(['like', 'autre_nom', $search])
	            ->limit(20);
	        $command = $query->createCommand();
	        $data = $command->queryAll();
	        $out['results'] = array_values($data);
	    }
	    elseif ($id > 0) {
			$client = Client::findOne($id);
			$addr = $client->makeAddress(true, $ret);//$this->render('_header_client', ['client' => $client])
	        $out['results'] = ['id' => $id, 'text' => $client->nom, 'addr' => $addr];
	    }
	    else {
	        $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
	    }
	    echo Json::encode($out);
	}
	
	public function actionGetItem($id) {
        if (($model = Item::findOne($id)) !== null) {
	    	echo Json::encode(['item' => $model->attributes]);
		} else
        	throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionTerminate($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_DONE);
		/*
		$model = $this->findModel($id);
		if($work = $model->getWorks()->one())
			$work->terminate(); // should only be one, at most
		$model->refresh();
        return $this->render('view', [
            'model' => $model,
        ]);
		*/
	}

	public function actionConvert($id) {
		$model = $this->findModel($id);
		if($model->document_type == Document::TYPE_ORDER && $model->bom_bool) {
			// all termnated and unbilled orders for same client
			$query = Order::find()->where(['bom_bool' => true, 'client_id' => $model->client_id, 'status' => Document::STATUS_DONE]);
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);
	        return $this->render('boms', [
	            'dataProvider' => $dataProvider,
	        ]);
		} else {
			$order = $model->convert();
	        return $this->render('view', [
	            'model' => $order,
	        ]);
		}
	}

	public function actionBillBoms() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				$bill = Bill::createFromBoms($_POST['selection']);
				if($bill)
					return $this->redirect(['view', 'id' => $bill->id]);
				else
					Yii::$app->session->setFlash('info', Yii::t('store', 'The bill was not created.'));
			} else {
					Yii::$app->session->setFlash('info', Yii::t('store', 'There is no bill in selection.'));
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionSubmit($id) {
		$model = $this->findModel($id);
		$work = $model->createWork();
		if($work)
        	return $this->redirect(['/work/work/view', 'id' => $work->id, 'sort' => 'position']);
		else {
			Yii::$app->session->setFlash('info', Yii::t('store', 'There is no work for this order.'));
        	return $this->redirect(Yii::$app->request->referrer);			
		}
	}

	protected function actionUpdateStatus($id, $status) {
		Yii::trace('DocumentController::actionUpdateStatus:'.$status.'.');
		$model = $this->findModel($id);
		$model->setStatus($status);
        return $this->render('view', [
            'model' => $model,
        ]);
	}

	public function actionPaid($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_CLOSED);
	}

	public function actionPay() {
		$capturePayment = new CapturePayment();
		
		if($capturePayment->load(Yii::$app->request->post())) {
			$model = $this->findModel($capturePayment->id);
			// record paiement
			$payment = new Payment([
				'document_id' => $model->id,
				'payment_method' => $capturePayment->method,
				'amount' => $capturePayment->amount,
				'status' => Payment::STATUS_PAID,
			]);
			$payment->save();
			
			if($model->prepaid == '') $model->prepaid = 0;
			$model->prepaid += $capturePayment->amount;
			$model->save();

			$solde = $model->price_tvac - $model->prepaid;
			Yii::trace('Solde:'.$solde.'.');

			$work = null;
			if($capturePayment->submit) {
				$work = $model->createWork();
			}
			return $this->actionUpdateStatus($model->id, $work ? $work->getOrderStatus() : ($solde < 0.01 ? Document::STATUS_CLOSED : Document::STATUS_SOLDE));
		}
		Yii::$app->session->setFlash('danger', Yii::t('store', 'There was a problem reading payment capture.'));
	}

	public function actionClose($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_CLOSED);
	}

	public function actionSent($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_NOTE);
	}

	public function actionCancel($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_CANCELLED);
	}

	protected function generatePdf($model, $filename = null) {
	    $header  = $this->renderPartial('_print_header', ['model' => $model]);
	    $content = $this->renderPartial('_print', ['model' => $model]);
	    $footer  = $this->renderPartial('_print_footer', ['model' => $model]);

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

	public function actionSend() {
		$captureEmail = new CaptureEmail();
		
		if($captureEmail->load(Yii::$app->request->post())) {
			$model = $this->findModel($captureEmail->id);

			if($captureEmail->save) {
				$client = Client::findOne($model->client_id);
				$client->email = $captureEmail->email;
				$client->save();
			}
			
			if($captureEmail->email != '') {
				$filename = tempnam('/var/tmp', 'yiipdf-'.$model->name.'-');
				$pdf = $this->generatePdf($model, $filename);			
				Yii::$app->mailer->compose()
				    ->setFrom('labojjmicheli@gmail.com')
				    ->setTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : $captureEmail->email )  // <======= FORCE DEV EMAIL TO TEST ADDRESS
				    ->setSubject(Yii::t('store', $model->document_type).' '.$model->name)
					->setTextBody($captureEmail->body)
					->attach($filename, ['fileName' => $subject.'.pdf', 'contentType' => 'application/pdf'])
				    ->send();
				Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has no email address.'));
			}
			//unlink($filename);
		}

		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionPdf($id) {
		$model = $this->findModel($id);
		return $this->generatePdf($model);
	}

	public function actionHtml($id) {
		$model = $this->findModel($id);
		return $this->render('print', ['model' => $model]);
	}

	public function actionPrint($id) {
		return $this->actionPdf($id);
	}

	public function actionLabels($id) {
		$model = $this->findModel($id);

	    $header  = $this->renderPartial('_print_header', ['model' => $model]);
	    $content = $this->renderPartial('_label', ['model' => $model]);
	    $footer  = $this->renderPartial('_print_footer', ['model' => $model]);

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
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
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

    	$pdf = new Pdf($pdfData);
		return $pdf->render();
	}

}
