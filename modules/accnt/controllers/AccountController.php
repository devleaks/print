<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\PdfDocumentGenerator;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Bill;
use app\models\CaptureBalance;
use app\models\Client;
use app\models\ClientSearch;
use app\models\CoverLetter;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
    public function actionClient2($id)
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


    public function actionClient($id)
    {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$date_lim = date('Y-m-d', strtotime('60 days ago')); //'7 days ago'
		
		$opening_balance = Account::getBalance($client->id, $date_lim);
		$closing_balance = Account::getBalance($client->id);
		
        $dataProvider = new ActiveDataProvider([
			'query' => Account::find()
							->andWhere(['client_id' => $client->id])
							->andWhere(['>=','created_at',$date_lim])
		]);

        return $this->render('client', [
            'dataProvider' => $dataProvider,
			'to_date' => $date_lim,
			'opening_balance' => $opening_balance,
			'closing_balance' => $closing_balance,
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
    public function actionBalance($id) {
		$capture = new CaptureBalance();
		$capture->client_id = $id;

        if ($capture->load(Yii::$app->request->post())) {
			if($capture->client_id == '') $capture->client_id = $id;
			// 1. Do we have bills checked?
			if(isset($_POST)) {
				if(isset($_POST['selection'])) {
					if(count($_POST['selection']) > 0) {
						// get balance, and lines to pay
						$unpaids = Account::getUnpaid($capture->client_id);
						$balance = Account::getBalance($capture->client_id);
						$available = $unpaids < $balance ? abs($unpaids - $balance) : 0; // money left on account
						Yii::trace('Client:'.$capture->client_id.', unpaid='.$unpaids.', balance='.$balance.', avail before='.$available, 'AccountController::actionBalance');
						// record payment
						$deposit = str_replace(',','.',$capture->amount);
						$available += $deposit;
						Yii::trace('deposit='.$deposit.', avail after='.$available, 'AccountController::actionBalance');
						$payment_ok = true;
						$note = '';
						foreach(Account::find()->where(['id' => $_POST['selection']])->each() as $accnt_line) {
							Yii::trace('Left='.$available, 'AccountController::actionBalance');
							
							$needed = $accnt_line->status == Account::TYPE_DEBIT ? abs(round($accnt_line->amount,2)) : 0;
							if( ($available - $needed) > -0.009 ) { // had enough money to pay; rounding needed to the 0.001
								if($accnt_line->document_id) {
									$bill = Bill::findDocument($accnt_line->document_id);
									$note .= $bill->name.', ';
								} else
									$note .= $accnt_line->amount.'€, ';
									
								$accnt_line->status = Account::TYPE_BALANCED;
								$accnt_line->save();
								$available -= $needed;
								Yii::trace('Using='.$needed.', left='.$available, 'AccountController::actionBalance');
							} else { // no enough money to pay account
								Yii::trace('Needed='.$needed.', only '.
										$available.' left. ('.($available - $needed).')', 'AccountController::actionBalance');
								$payment_ok = false;
							}
						}
						$note = ($note == '') ? null : substr(trim($note, ', '), 0, 158);
						$payment = new Account([
							'amount' => str_replace(',','.',$capture->amount),
							'status' => 'ACREDIT',
							'client_id' => $capture->client_id,
							'note' => $note,
							'payment_date' => $capture->date,
							'payment_method' => $capture->method,
						]);
						if(!$payment->save())
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Error: {0}', [print_r($payment->errors, true)]));

						if($payment_ok) {
							$capture = new CaptureBalance();
							Yii::$app->session->setFlash('success', Yii::t('store', 'Account lines sucessfully balanced.'));
						} else {
							Yii::$app->session->setFlash('warning', Yii::t('store', 'Amount is not sufficient to balance all account lines.'));
						}
					} else {
						Yii::$app->session->setFlash('danger', Yii::t('store', 'You did not check any bill.'));
					}
				} else {
					Yii::$app->session->setFlash('danger', Yii::t('store', 'You did not check any bill.'));
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
	 *
	 *
	 */
	public function actionBulkNotify() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {
					$clg = new PdfDocumentGenerator($this);
					foreach($_POST['selection'] as $client_id) {
						$clg->accountExtract($client_id, true);
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
