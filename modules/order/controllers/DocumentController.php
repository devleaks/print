<?php

namespace app\modules\order\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\Cash;
use app\models\Bid;
use app\models\BidSearch;
use app\models\Bill;
use app\models\BillSearch;
use app\models\CaptureEmail;
use app\models\CapturePayment;
use app\models\CaptureSearch;
use app\models\CaptureSelection;
use app\models\Client;
use app\models\Credit;
use app\models\CreditSearch;
use app\models\Document;
use app\models\DocumentLine;
use app\models\DocumentSearch;
use app\models\Item;
use app\models\Order;
use app\models\OrderSearch;
use app\models\PDFLabel;
use app\models\Parameter;
use app\models\Payment;
use app\models\PaymentLink;
use app\models\PrintedDocument;
use app\models\Refund;
use app\models\RefundSearch;
use app\models\Sequence;
use app\models\Ticket;
use app\models\TicketSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class DocumentController extends Controller
{
	const TYPE_BOM = 'BOM';
	const ACTION_CONVERT = 'CONVERT';

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
	                    'roles' => ['admin', 'manager', 'compta', 'employee', 'frontdesk'],
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex2() {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionBulk() {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('bulk', [
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
    public function actionChangeClient($id) {
		$model = $this->findModel($id);
		
		if($model->bom_bool) {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'You cannot change the client of a Bill of Material or of a bill of BoM.'));
			return $this->redirect(Yii::$app->request->referrer);
		} else if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('store', '{document} updated.', ['document' => Yii::t('store', $model->document_type)]));
        }
        return $this->render('change-client', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionSearch() {
		$model = new CaptureSearch();
		$model->load(Yii::$app->request->post());
		
		$searchModel = new DocumentSearch();
		if($model->search)
			$searchModel->search = $model->search;
			
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if($searchModel->search) {
			$dataProvider->query
				->with('client')
				->orWhere(['like', 'document.name', $searchModel->search])
				->orWhere(['like', 'document.sale', $searchModel->search])
				->orWhere(['like', 'document.reference', $searchModel->search])
				->orWhere(['like', 'document.reference_client', $searchModel->search])
				->orWhere(['like', "lower(replace(document.name, '-', ''))", str_replace('-', '', strtolower($searchModel->search))])
				->orWhere(['like', "replace(document.reference, '/', '')", $searchModel->search])
				->orWhere(['like', 'document.note', $searchModel->search])
				->orWhere(['like', 'client.nom', $searchModel->search])
				->orWhere(['like', 'client.autre_nom', $searchModel->search])
				->orderBy('updated_by desc');
		}
		$dataProvider->sort = false;
		return $this->render('index', [
			'searchModel' => null, // $searchModel,
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
					$model_line->extra_type = DocumentLine::EXTRA_REBATE_AMOUNT;
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
					$model_line->extra_type = DocumentLine::EXTRA_REBATE_AMOUNT;
					$model_line->save();
					return $this->redirect(['document-line/update', 'id' => $model_line->id]);
				}
				$addDocumentLine = DocumentLineController::addFirstLine($model);
				$model->updatePrice();
				
				$cancel = Html::a(Yii::t('store', 'Cancel'),
								['/order/document/delete', 'id'=>$model->id],
								[
									'data-method' => 'post',
									'title' => Yii::t('store', 'Delete {0}', Yii::t('store', $model->document_type)),
									'data-confirm' => Yii::t('store', 'Delete {0}?', Yii::t('store', $model->document_type)),
								]);
				Yii::$app->session->setFlash('success', Yii::t('store', 'Document added. {0}.', $cancel));
				
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
		if($cli = Client::auComptoir())
			$id = $cli->id;
        return $this->actionCreate($id, Document::TYPE_REFUND);
    }

    public function actionCreateTicket($id = null) {
		if($cli = Client::auComptoir())
			$id = $cli->id;
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
    public function actionDelete($id) { //@todo: Put in transaction
		$model = $this->findModel($id);
		$cnt = $model->getDocuments()->count();
		$cash_cnt = $model->getCashes()->count();
		$payment_cnt = $model->getPayments()->count();
		Yii::trace('cnt='.$cnt.',payments='.$cash_cnt, 'DocumentController::actionDelete');
		if ( ($cash_cnt > 0 || $model->soloOwnsPayments()) || $payment_cnt > 0 ) {
			Yii::$app->session->setFlash('error', Yii::t('store', 'This document cannot be deleted because there are payment attached to it. You must delete payment(s) first.'));
		} else if($cnt > 0 && !($model->document_type == Document::TYPE_BILL && $model->bom_bool)) {
			Yii::$app->session->setFlash('error', Yii::t('store', 'This document cannot be deleted because a document depends on it.'));
		} else {
			$ok = true;
			if($model->bom_bool) {
				if($model->document_type == Document::TYPE_BILL) { // remove pointer from BOM to this bill if any.
					foreach(Document::find()->where(['parent_id' => $model->id])->each() as $bom) {
						$bom->parent_id = null;
						$bom->bill_id = null;
						$bom->setStatus(Document::STATUS_TOPAY);
						$bom->save();
					}
				} else if ($model->document_type == Document::TYPE_ORDER) {
					if($bill = $model->getBill()) {
						Yii::$app->session->setFlash('error', Yii::t('store', 'This document cannot be deleted because a document depends on it.'));
						$ok = false;
					}
				}
			}
			if($ok) {
	        	$model->deleteCascade();
				Yii::$app->session->setFlash('success', Yii::t('store', 'Document deleted.'));
			}
		}

		return $this->redirect(Url::to(['/order/document', 'sort' => '-updated_at']));
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
	        $query->select(['id', 'text' => 'nom'])
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
			$addr = $client->makeAddress(! $client->isComptoir(), $ret);//$this->render('_header_client', ['client' => $client])
	        $out['results'] = ['id' => $id, 'text' => $client->nom, 'addr' => $addr];
	    }
	    else {
	        $out['results'] = ['id' => 0, 'text' => 'No matching client found'];
	    }
	    echo Json::encode($out);
	}
	
	public function actionDocumentList($search = null, $sale = null, $ret = null) {
	    $out = ['more' => false];
	    if (!is_null($search)) {
	        $query = new Query;
	        $query->select(['id' => 'sale', 'text' => 'concat(name," - ",sale)'])
	            ->from('document')
	            ->orWhere(['like', 'name', $search])
	            ->orWhere(['like', 'reference', $search])
	            ->orWhere(['like', 'sale', $search])
	            ->limit(20);
	        $command = $query->createCommand();
	        $data = $command->queryAll();
	        $out['results'] = array_values($data);
	    }
	    elseif ($sale > 0) {
			$doc = Document::findOne(['sale' => $sale]);
			Yii::trace('sale'.$sale."=".$doc->name);
	        $out['results'] = ['id' => $doc->sale, 'text' => $doc->name.' - '.$doc->sale];
	    }
	    elseif ($id > 0) {
			$doc = Document::findOne(['id' => $id]);
			Yii::trace('id'.$id."=".$doc->name);
	        $out['results'] = ['id' => $doc->sale, 'text' => $doc->name.' - '.$doc->sale];
	    }
	    else {
	        $out['results'] = ['id' => 0, 'text' => 'No matching document found', 'id' => 0];
	    }
		Yii::trace('ret='.print_r($out,true));
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
			return $this->actionUpdateStatus($model->id, $model->isPaid() ? Document::STATUS_DONE : Document::STATUS_TOPAY);
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

	public function actionReceive($id) {
		$model = $this->findModel($id);
		if($model->document_type == Document::TYPE_TICKET) {			
			$solde = $model->getBalance();
			return $this->actionUpdateStatus($model->id, $model->isPaid() ? Document::STATUS_CLOSED : Document::STATUS_TOPAY);
		}
		else
			return $this->actionUpdateStatus($id, Document::STATUS_CLOSED);
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
			$query = Order::find()->andWhere(['bom_bool' => true, 'client_id' => $model->client_id])
								  //->andWhere(['status' => [Document::STATUS_DONE, Document::STATUS_NOTIFY, Document::STATUS_TOPAY, Document::STATUS_CLOSED]])
								  ->andWhere(['bill_id' => null]);
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);
	        return $this->render('boms', [
	            'dataProvider' => $dataProvider,
				'capture' => new CaptureSelection()
	        ]);
		} else {
			if($model->client->isComptoir()) {
				$change_client = Html::a(Yii::t('store', 'Change client'),
								['/order/document/change-client', 'id'=>$model->id],
								[
									'title' => Yii::t('store', 'Change client for this order.'),
								]);
				Yii::$app->session->setFlash('error', Yii::t('store', 'You cannot convert a sale ticket with no client. {0}.', $change_client));
		        return $this->render('view', [
		            'model' => $model,
		        ]);
			}
			$ticket = $model->document_type == Document::TYPE_TICKET ? 1 : 0;
			$order = $model->convert($ticket);
			$cancel = Html::a(Yii::t('store', 'Cancel'),
							['/order/document/cancel-convert', 'id'=>$order->id, 'ticket' => $ticket],
							[
								'data-method' => 'post',
								'title' => Yii::t('store', 'Cancel'),
								'data-confirm' => Yii::t('store', 'Cancel?'),
							]);
			Yii::$app->session->setFlash('success', Yii::t('store', 'Successful convertion. {0}.', $cancel));
	        return $this->render('view', [
	            'model' => $order,
	        ]);
		}
	}


	public function actionCopy($id) {
		$model = $this->findModel($id);
		$copy  = $model->deepCopy();

		$now = date('Y-m-d', strtotime('now'));
		switch($copy->document_type) {
			case Document::TYPE_BILL:
				$copy->name = substr($now,0,4).'-'.Sequence::nextval('bill_number');
				break;
			case Document::TYPE_CREDIT:
				$copy->name = substr($now,0,4).'-'.Sequence::nextval('credit_number');
				break;
			default:
				$o = Parameter::getTextValue('application', $copy->bom_bool ? 'BOM' : $copy->document_type, '-');
				$copy->name = substr($now,0,4).$o.Sequence::nextval('doc_number');
				break;
		}
		$copy->save();

		$cancel = Html::a(Yii::t('store', 'Cancel'),
						['/order/document/delete', 'id'=>$copy->id],
						[
							'data-method' => 'post',
							'title' => Yii::t('store', 'Cancel'),
							'data-confirm' => Yii::t('store', 'Cancel?'),
						]);
		$cancel = "";
		Yii::$app->session->setFlash('success', Yii::t('store', 'Copied. {0}.', $cancel));
        return $this->render('view', [
            'model' => $copy,
        ]);
	}
	

	public function actionCancelConvert($id, $ticket) {
		if( $model = $this->findModel($id) ) {
			$parent = $this->findModel($model->parent_id);
			$what = $model->document_type;
			$model->deleteCascade();
			if($ticket == 1) { // it was a ticket sale, restore its type
				$parent->document_type = Document::TYPE_TICKET;
			}
			$parent->setStatus($parent->document_type == Document::TYPE_BID ? Document::STATUS_OPEN : Document::STATUS_TOPAY);
			Yii::$app->session->setFlash('success', Yii::t('store', '{0} deleted.', Yii::t('store', $what)));
	        return $this->render('view', [
	            'model' => $parent,
	        ]);
		}
		Yii::$app->session->setFlash('danger', Yii::t('store', 'Document {0} not found.', $id));
		return $this->actionIndex();
	}

	public function actionBillBoms() {
		$model = new CaptureSelection();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			//Yii::trace('trying...', 'DocumentController::actionBillBoms');
			if(!is_array($model->selection))
				$model->selection = explode(',',trim($model->selection));
			$bill = Bill::createFromBoms($model->selection);
			if($bill)
				return $this->redirect(Url::to(['view', 'id' => $bill->id]));
			else
				Yii::$app->session->setFlash('info', Yii::t('store', 'The bill was not created.'));
		} else {
				Yii::$app->session->setFlash('info', Yii::t('store', 'There is no bill in selection.'));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionSubmit($id) {
		$model = $this->findModel($id);
		$work = $model->createWork();
		if($work) {
			$cancel = Html::a(Yii::t('store', 'Cancel work submission'),
							['/work/work/delete', 'id'=>$work->id],
							[
								'data-method' => 'post',
								'title' => Yii::t('store', 'Delete work order'),
								'data-confirm' => Yii::t('store', 'Delete work order?'),
							]);
			Yii::$app->session->setFlash('success', Yii::t('store', 'Work submitted. {0}.', $cancel));
        	return $this->redirect(['/work/work/view', 'id' => $work->id, 'sort' => 'position']);
		} else {
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
			if ($capturePayment->validate()) {

				$capturePayment->amount = str_replace(',', '.', $capturePayment->amount);
				$capturePayment->total  = str_replace(',', '.', $capturePayment->total);
				
				$model = $this->findModel($capturePayment->id);

				// submit work before dealing with paymebts
				$feedback = '';
				if($capturePayment->submit) { // do we need to create a new work order for this order?
					$cancel = '';
					if($work = $model->createWork()) {					
						$cancel = Html::a(Yii::t('store', 'Cancel work submission'),
										['/work/work/delete', 'id'=>$work->id],
										[
											'data-method' => 'post',
											'title' => Yii::t('store', 'Delete work order'),
											'data-confirm' => Yii::t('store', 'Delete work order?'),
										]);
					}
					$feedback = $work ? Yii::t('store', 'Work submitted ({0})', $cancel) : Yii::t('store', 'No work to submit');
				}

				// deal with payment in a single transaction: Everything OK or fail.
				$transaction = Yii::$app->db->beginTransaction();

				$payment_entered = null;
				if($capturePayment->method != Payment::USE_CREDIT) { // if we use credit, money is already here, so we don't add it
					$cash = null;
					if($capturePayment->method == Payment::CASH) {
						$cash = new Cash([
							'document_id' => $model->id,
							'sale' => $model->sale,
							'amount' => $capturePayment->amount,
							'payment_date' => date('Y-m-d'),
							'note' => $capturePayment->note,
						]);
						$cash->save();
						$cash->refresh();
					}
					$payment_entered = new Account([
						'client_id' => $model->client_id,
						'payment_method' => $capturePayment->method,
						'payment_date' => date('Y-m-d H:i:s'),
						'amount' => $capturePayment->amount,
						'status' => $capturePayment->amount > 0 ? 'CREDIT' : 'DEBIT',
						'cash_id' => $cash ? $cash->id : null,
						'note' => $capturePayment->note,
					]);
					$payment_entered->save();
					$payment_entered->refresh();
				}

				if( $model->addPayment($payment_entered, $capturePayment->amount, $capturePayment->method, $capturePayment->note) ) {
					Yii::trace('doc='.$model->document_type, 'DocumentController::actionUpdateStatus');
					$model->setStatus(Order::STATUS_TOPAY);
					if($model->document_type == $model::TYPE_REFUND && $capturePayment->use_credit) {
						Yii::trace('capture use_credit', 'DocumentController::actionDelete');
						$model->credit_bool = true;
						$model->save();
						$credit_payment = $model->getPayments()->one();
						foreach(Payment::find()->andWhere(['client_id' => $model->client_id, 'status' => Payment::STATUS_OPEN])->each() as $payment) {
							$payment->status = Payment::STATUS_PAID;
							$payment->save();
							$link = new PaymentLink([
								'payment_id' => $credit_payment->id,
								'linked_id' => $payment->id
							]);
							$link->save();
						}
					}
					Yii::$app->session->setFlash('success', ($feedback ? $feedback . '; '.strtolower(Yii::t('store', 'Payment added')): Yii::t('store', 'Payment added')).'.');
					$transaction->commit();
				} else {
					Yii::$app->session->setFlash('danger', ($feedback ? $feedback . '; '.strtolower(Yii::t('store', 'Payment was not added')): Yii::t('store', 'Payment was not added')).'.');
					$transaction->rollback();
				}

			} else { // report capture errors
				Yii::$app->session->setFlash('danger', Yii::t('store', 'There was a problem capturing payment: {0}.',
				 		VarDumper::dumpAsString($capture->errors, 4, true)));
			}
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

	public function actionSent2($id) {
		$model = $this->findModel($id);
		$sent = false;
		if($model->document_type == Document::TYPE_ORDER)
			$sent = $model->notify();
		if($sent)
			$model->setStatus(Document::STATUS_TOPAY);
        return $this->render('view', [
            'model' => $model,
        ]);
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
					'document' => $model,
					'save' => true,
					'images' => true,
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
		$f = $format == PrintedDocument::IMAGES ? PrintedDocument::FORMAT_A4 : $format;
		$pdf = new PrintedDocument([
			'document'	=> $model,
			'format'	=> $f,
			'images'	=> ($format == PrintedDocument::IMAGES),
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


	/**
	 * Bulk process document for PJAXed gridview.
	 */
    public function actionBulkAction()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$status = Yii::$app->request->post('action');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		if($status == DocumentController::ACTION_CONVERT)
			foreach($ids as $id) {
				if($d = Document::findDocument($id)) {
					$d->convert();
				}
	        }
    }

}
