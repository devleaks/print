<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\components\PdfDocumentGenerator;
use app\models\Account;
use app\models\Attachment;
use app\models\Bill;
use app\models\BillSearch;
use app\models\Client;
use app\models\CoverLetter;
use app\models\Order;
use app\models\OrderSearch;
use app\models\Payment;
use kartik\mpdf\Pdf;
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
	

	public function actionBulkAction() {
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
								$clg = new PdfDocumentGenerator($this);
								$dirName = RuntimeDirectoryManager::getPath(RuntimeDirectoryManager::PATH_LATE_BILLS);
								$viewBase = '@app/modules/accnt/views/bill/';
								
								$q =  Bill::find()
											->andWhere(['document.id' => $_POST['selection']])
											->andWhere(['!=','document.status',Bill::STATUS_CLOSED])
//											->andWhere(['<=','created_at',$late])
											->orderBy('client_id, created_at asc'); // latest bill first
								// loop over clients:
								$client_id = -1;
								$bills = [];
								$docs  = [];
								foreach($q->each() as $bill) {
									if($client_id == -1) $client_id = $bill->client_id;

									// $fn = $this->generateBill($bill);
									$days = floor( (time() - strtotime($bill->created_at)) / (60*60*24) );
									$type = floor($days / 30);
									if($type > 3) $type = 3;
									$watermark = Yii::t('store', 'Reminder Type '.$type);
									$fn = $clg->document($bill, $dirName, $viewBase, $watermark);
									$docs[] = new Attachment(['filename' => $fn, 'title' => $bill->name]);
									$bills[] = $bill;
	
									// generate cover for previous
									if($bill->client_id != $client_id) {
										$clg->lateBills($bill->client_id, $bills, $docs, true);
										$bills= [];
										$docs = [];
										$client_id = $bill->client_id;
									}
								}
								// generate cover for last
								if($client_id != -1) $clg->lateBills($client_id, $bills, $docs, true);

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
