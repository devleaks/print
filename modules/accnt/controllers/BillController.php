<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\PdfDocumentGenerator;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\Attachment;
use app\models\Bill;
use app\models\BillSearch;
use app\models\CaptureBalance;
use app\models\Client;
use app\models\Order;
use app\models\OrderSearch;
use app\models\Payment;
use app\models\PrintedDocument;
use app\models\Sequence;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
    public function actionClientUnpaid($id) {
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

	protected function actionUpdateStatus($id, $status) {
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

		Yii::$app->session->setFlash('warning', Yii::t('store', 'No document selected.'));
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	
	public function actionAddPayment() {
		$capture = new CaptureBalance();
        if ($capture->load(Yii::$app->request->post())) {
			if(isset($_POST))
				if(isset($_POST['selection']))
					if(count($_POST['selection']) > 0) {
						$available = str_replace(',','.',$capture->amount);

						$more_needed = 0;
						Yii::trace('available='.$available, 'BillController::actionAddPayment');
						$q = Bill::find()->andWhere(['id'=>$_POST['selection']]);
						$client_id = null;
						foreach($q->each() as $b) {
							if($available > 0) {
								$needed = $b->getBalance();
								Yii::trace('needed='.$needed.' for '.$b->id, 'BillController::actionAddPayment');
								if($needed <= $available) {
									$b->addPayment($needed, $capture->method);
									$available -= $needed;
									Yii::trace('found, available='.$available, 'BillController::actionAddPayment');
								} else {
									$b->addPayment($available, $capture->method);
									$more_needed = $needed - $available;
									$available = 0;
									Yii::trace('NOT found, missing='.$more_needed, 'BillController::actionAddPayment');
								}
							} else {
								$more_needed += $b->getBalance();
							}
						}
						Yii::trace('Bottomline: missing='.$more_needed.', available='.$available, 'BillController::actionAddPayment');
						$available = round($available, 2);
						if($available > 0) {
							$remaining = new Payment([
								'sale' => Sequence::nextval('sale'), // its a new sale transaction...
								'client_id' => $capture->client_id,
								'payment_method' => $capture->method,
								'amount' => $available,
								'status' => Payment::STATUS_OPEN,
							]);
							$remaining->save();
							Yii::$app->session->setFlash('info', Yii::t('store', 'Transfered amount exceeds amount to pay all bills: {0}€ remaining.', $available,2));
						} else if($more_needed > 0) {
							Yii::$app->session->setFlash('warning', Yii::t('store', 'Transfered amount was not sufficiant to pay all bills: {0}€ missing.', $more_needed));
						} else {
							Yii::$app->session->setFlash('success', Yii::t('store', 'Transfered amount split in all bills.'));
						}
						$amount = str_replace(',','.',$capture->amount);
						
						$payment_entered = new Account([
							'sale' => null,
							'client_id' => $capture->client_id,
							'document_id' => null,
							'payment_method' => $capture->method,
							'amount' => $amount,
							'status' => $amount > 0 ? 'CREDIT' : 'DEBIT',
						]);
						$payment_entered->save();
					}
		}
		return $this->redirect(['index']);
	}
	

	public function actionBulkAction() {
		if(isset($_POST))
			if(isset($_POST['selection'])) {
				$selection = trim($_POST['selection']) != '' ? explode(',', $_POST['selection']) : [];
				if(count($selection) > 0) {
					if(isset($_POST['action'])) {
						//Yii::trace($_POST['action'].' for '.$_POST['selection'], 'BillController::actionBulkAction');
						$action = $_POST['action'];
						if(in_array($action, [Bill::ACTION_PAYMENT_RECEIVED, Bill::ACTION_SEND_REMINDER, Bill::ACTION_CLIENT_ACCOUNT])) {
							if($action == Bill::ACTION_PAYMENT_RECEIVED) {
								$bills = Bill::find()->andWhere(['id'=>$selection]);
								$q = clone $bills;
								$clients = [];
								foreach($q->each() as $b)
									$clients[$b->client_id] = Client::findOne($b->client_id)->niceName();
						        return $this->render('add-payment', [
						            'dataProvider' => new ActiveDataProvider([
											'query' => $q,	
									]),
									'clients' => $clients,
									'capture' => new CaptureBalance(),
						        ]);
							} else { // ACTION_SEND_REMINDER, loop per client
								$clg = new PdfDocumentGenerator($this);
								
								$q =  Bill::find()
											->andWhere(['document.id' => $selection])
											->andWhere(['!=','document.status',Bill::STATUS_CLOSED])
//											->andWhere(['<=','created_at',$late])
											->orderBy('client_id, created_at asc'); // latest bill first
								// loop over clients:
								$client_id = -1;
								$bills = [];
								$docs  = [];
								foreach($q->each() as $bill) {
									Yii::trace($bill->name, 'BillController::actionBulkAction');
									if($client_id == -1) $client_id = $bill->client_id;
									// generate cover for previous client if we just changed
									if($bill->client_id != $client_id) {
										$clg->lateBills($client_id, $bills, $docs, true);
										$bills= [];
										$docs = [];
										$client_id = $bill->client_id;
									}

									$days = floor( (time() - strtotime($bill->created_at)) / (60*60*24) );
									$type = floor($days / 30);
									if($type > 3) $type = 3;
									$pdf = new PrintedDocument([
										'document'		=> $bill,
										'watermark'		=> Yii::t('print', 'Reminder Type '.$type),
										'save'			=> true,
									]);
									$fn = $pdf->render();
									Yii::trace('Adding: '.$fn, 'BillController::actionBulkAction');
									$docs[] = new Attachment(['filename' => $fn, 'title' => $bill->name]);
									$bills[] = $bill;	
								}
								// generate cover for last
								if($client_id != -1) {
									$clg->lateBills($client_id, $bills, $docs, true);
								}

								Yii::$app->session->setFlash('warning', Yii::t('store', 'Reminders sent and/or ready to print.'));
							}
						}
					}
					return $this->redirect(Yii::$app->request->referrer);
				}
			}

		Yii::$app->session->setFlash('warning', Yii::t('store', 'No document selected.'));
		return $this->redirect(Yii::$app->request->referrer);
	}
	
}
