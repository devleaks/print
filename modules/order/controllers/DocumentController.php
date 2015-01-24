<?php

namespace app\modules\order\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\PrintedDocument;
use app\models\PDFLabel;
use app\models\Account;
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
use app\models\Refund;
use app\models\RefundSearch;
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
	const TYPE_BOM = 'BOM';

	/**
	 *  Sets global behavior for database line create/update and basic security
	 */
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
	                    'roles' => ['admin', 'manager', 'compta', 'employee'],
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

    /**
     * Lists all Order models for supplied client.
     * @return mixed
     */
	public function actionClient($id) {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');
		
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['client_id' => $id]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'client' => $client
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

    public function actionRefunds() {
        return $this->actionIndexByType(new RefundSearch(), Document::TYPE_REFUND);
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
				->orWhere(['like', 'document.note', $search])
				->orWhere(['like', 'client.nom', $search])
				->orWhere(['like', 'client.autre_nom', $search])
				->orderBy('updated_by desc');

        return $this->render('doc', [
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

		if($type == self::TYPE_BOM) {
			$model->document_type = Document::TYPE_ORDER;
			$model->bom_bool = true;
		}

        if ($model->load(Yii::$app->request->post())) { // we just create the order
			if(!isset($model->document_type)) $model->document_type = $type;

			if(!isset($model->name)) {
				$now = date('Y-m-d', strtotime('now'));
				switch($model->document_type) {
					case Document::TYPE_BILL:
						$model->name = substr($now,0,4).'-'.Sequence::nextval('bill_number');
						break;
					case Document::TYPE_CREDIT:
						$model->name = substr($now,0,4).'-'.Sequence::nextval('credit_number');
						break;
					default:
						$o = Parameter::getTextValue('application', $model->bom_bool ? 'BOM' : $model->document_type, '-');
						$model->name = substr($now,0,4).$o.Sequence::nextval('doc_number');
						break;
				}
			}
			// temporaily set reference
			$model->sale = Sequence::nextval('sale'); // Document::commStruct($model->name);//$model->name;
			$model->reference = Document::commStruct(date('y')*10000000 + $model->sale);//$model->name;
			if(!$model->priority) $model->priority = 100; // test
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
				} else if($model->document_type == Document::TYPE_REFUND) {
					$credit_item = Item::findOne(['reference' => Item::TYPE_REFUND]);
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
				}
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

    public function actionCreateBom($id = null) {
        return $this->actionCreate($id, self::TYPE_BOM);
    }

    public function actionCreateBill($id = null) {
        return $this->actionCreate($id, Document::TYPE_BILL);
    }

    public function actionCreateCredit($id = null) {
        return $this->actionCreate($id, Document::TYPE_CREDIT);
    }

    public function actionCreateRefund($id = null) {
		$cli = Client::findOne(['nom' => 'Client au comptoir']);
		if($cli) $id = $cli->id;
        return $this->actionCreate($id, Document::TYPE_REFUND);
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
		$paycnt = $model->getCashes()->count();
		if($cnt > 0)
			Yii::$app->session->setFlash('error', Yii::t('store', 'This document cannot be deleted because a child document depends on it.'));
		else if ($paycnt > 0) {
				Yii::$app->session->setFlash('error', Yii::t('store', 'This document cannot be deleted because there are payment attached to it.'));
		} else {  // ok to remove
			if($model->document_type == Document::TYPE_BILL && $model->bom_bool) { // remove pointer from BOM to this bill if any.
				foreach(Document::find()->where(['parent_id' => $model->id])->each() as $bom) {
					$bom->parent_id = null;
					$bom->save();
				}
			}			
        	$this->findModel($id)->deleteCascade();
			Yii::$app->session->setFlash('success', Yii::t('store', 'Document deleted.'));
		}

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
	
	public function actionGetItemByRef($ref) {
        if (($model = Item::findOne(['reference' => $ref])) !== null) {
	    	echo Json::encode(['item' => $model->attributes]);
		} else
        	throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionTerminate($id) {
		$model = $this->findModel($id);
		if($model->document_type == Document::TYPE_TICKET) {			
			$solde = $model->getBalance();
			return $this->actionUpdateStatus($model->id, $model->isPaid() ? Document::STATUS_DONE : Document::STATUS_SOLDE);
		}
		else
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

	public function actionConvert($id, $ticket = false) {
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
			$order = $model->convert($ticket);
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
		$model = $this->findModel($id);
		$model->setStatus($status);
        return $this->render('view', [
            'model' => $model,
        ]);
	}

	public function actionPay() {
		$capturePayment = new CapturePayment();
		
		if($capturePayment->load(Yii::$app->request->post())) {
			if(isset($_POST)) // ugly but ok for now
				if(isset($_POST['CapturePayment'])) {
					if(isset($_POST['CapturePayment']["amount"]))
						$capturePayment->amount = str_replace(',', '.', $_POST['CapturePayment']["amount"]);
					if(isset($_POST['CapturePayment']["total"]))
						$capturePayment->total = str_replace(',', '.', $_POST['CapturePayment']["total"]);
				}
			$model = $this->findModel($capturePayment->id);

			$ok = $model->addPayment($capturePayment->amount, $capturePayment->method);
			
			$feedback = '';
			if($capturePayment->submit) { // do we need to create a new work order for this order?
				$work = $model->createWork();
				$feedback = $work ? Yii::t('store', 'Work submitted') : Yii::t('store', 'No work to submit');
			}
			Yii::trace('doc='.$model->document_type, 'DocumentController::actionUpdateStatus');
			$model->updatePaymentStatus();
			Yii::trace('doc='.$model->document_type, 'DocumentController::actionUpdateStatus');
			if($ok) Yii::$app->session->setFlash('success', ($feedback ? $feedback . '; '.strtolower(Yii::t('store', 'Payment added')): Yii::t('store', 'Payment added')).'.');
	        return $this->render('view', [
	            'model' => $model,
	        ]);
		} else {
			Yii::$app->session->setFlash('danger', Yii::t('store', 'There was a problem reading payment capture.'));
			return $this->redirect(Yii::$app->request->referrer);
		}
	}

	public function actionClose($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_CLOSED);
	}

	public function actionSent($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_TOPAY);
	}

	public function actionCancel($id) {
		return $this->actionUpdateStatus($id, Document::STATUS_CANCELLED);
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
				$pdf = new PrintedDocument([
					'controller' => $this,
					'document' => $model,
					'save' => true,
				]);
				$pdf->send(Yii::t('store', $model->document_type).' '.$model->name, $captureEmail->body, $captureEmail->email);
				Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Client has no email address.'));
			}
			//unlink($filename);
		}

		return $this->redirect(Yii::$app->request->referrer);
	}


	public function actionPdf($id, $format = PrintedDocument::FORMAT_A4) {
		$model = $this->findModel($id);
		$pdf = new PrintedDocument([
			'controller' => $this,
			'document' => $model,
			'format'	=> $format,
		]);
		return $pdf->render();
	}

	public function actionHtml($id) {
		$model = $this->findModel($id);
		return $this->render('print', ['model' => $model]);
	}


	public function actionPrint($id, $format = PrintedDocument::FORMAT_A4) {
		if($format == 'html')
			return $this->actionHtml($id);
		return $this->actionPdf($id, $format);
	}


	public function actionLabels($id) {
		$model = $this->findModel($id);
		$pdf = new PDFLabel([
			'content' => $this->renderPartial('@app/modules/store/prints/label/order', ['model' => $model])
		]);		
		return $pdf->render();
	}

}
