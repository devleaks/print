<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\AccountSearch;
use app\models\CaptureAccountNoSale;
use app\models\Client;
use app\models\Credit;
use app\models\Document;
use app\models\History;
use app\models\Order;
use app\models\PDFAccount;
use app\models\Payment;
use app\models\Sequence;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
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
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['id' => Payment::find()->andWhere(['not', ['sale' => Document::find()->select('sale')]])->select('account_id')])
							->andWhere(['not', ['id' => Payment::find()->andWhere(['not',['account_id' => null]])->select('account_id')->groupBy('account_id')->having('count(account_id) > 1')]]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Account model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $capture = new CaptureAccountNoSale();

        if ($capture->load(Yii::$app->request->post()) && $capture->validate()) {
			$amount = str_replace(',','.',$capture->amount);
			$sale = Sequence::nextval('sale');
			$cash = null;
			if($capture->method == Payment::CASH) {
				$cash = new Cash([
					'sale' => $sale,
					'amount' => $amount,
					'payment_date' => $capture->date ? $capture->date : date('Y-m-d'),
				]);
				$cash->save();
				$cash->refresh();
			}
			$account = new Account([
				'client_id' => $capture->client_id,
				'payment_method' => $capture->method,
				'payment_date' => $capture->date ? $capture->date : date('Y-m-d'),
				'amount' => $amount,
				'status' => $amount > 0 ? 'CREDIT' : 'DEBIT',
				'cash_id' => $cash ? $cash->id : null,
			]);
			$account->save();
			$account->refresh();
			$payment = new Payment([
				'sale' => $sale, // its a new sale transaction, payment is not added to any existing sale
				'client_id' => $capture->client_id,
				'payment_method' => $capture->method,
				'amount' => $amount,
				'status' => Payment::STATUS_OPEN,
				'account_id' => $account->id,
				'cash_id' => $cash ? $cash->id : null,
			]);
			$payment->save();
            return $this->redirect(['/accnt/account/client', 'id' => $capture->client_id]);
        } else {
            return $this->render('create', [
                'model' => $capture,
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
			History::record($model, 'EDITED', 'Account modified.', true, null);
            return $this->redirect(['view', 'id' => $model->id]);
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
		$account = $this->findModel($id);
		foreach($account->getPayments()->each() as $payment) {
			$doc = Document::findBySale($payment->sale);
			if(!$doc && $payment->status == Payment::STATUS_OPEN) {
				$payment->delete();
			} else {
				$doc->deletePayment($payment->id, false);
			}
		}
		$account->deleteWithCash();
		Yii::$app->session->setFlash('success', Yii::t('store', 'Account line deleted. All payments deleted.'));
        return $this->redirect(['/accnt']);
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


/*
select 'A',id, client_id, price_tvac as amount, created_at from document
where client_id = 3019
union
select 'B',sale,client_id,amount,created_at from payment
where client_id = 3019
order by 5 desc
*/
	public function actionClient($id) {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$accountLines = $client->getAccountLines();

        return $this->render('client', [
            'dataProvider' => new ArrayDataProvider(['allModels' => $accountLines]),
			'client' => $client,
			'bottomLine' => !empty($accountLines) ? end($accountLines)->account : 0,
        ]);
	}
	
	public function actionClientPrint($id) {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$accountLines = $client->getAccountLines();

		$filename = RuntimeDirectoryManager::getFilename(RuntimeDirectoryManager::ACCOUNT, null, null, $client);
		$pdf = new PDFAccount([
			'content'		=> $this->renderPartial('_client_print', [
				'allModels' => $accountLines,
				'client' => $client,
				'bottomLine' => !empty($accountLines) ? end($accountLines)->account : 0,
	        ]),
			'client'		=> $client,
			'destination'	=> RuntimeDirectoryManager::ACCOUNT,
			'save'			=> true,
		]);
		$pdfDoc = $pdf->render();		
		return $this->redirect(['pdf/display', 'fn' => $pdfDoc]);
	}
	
	/**
	 * @param boolean $ticket Credit note (0) or simple refund (1)
	 */
	public function actionRefund($id, $ticket = 0) {
		if($payment = Payment::findOne($id)) {
			$newSale = Sequence::nextval('sale');
			$newReference = Document::commStruct(date('y')*10000000 + $newSale);
			$credit = null;
			if($ticket == 1) {
				$credit = new Refund([
					'document_type' => Refund::TYPE_REFUND,
					'client_id' => $payment->client_id,
					'name' => substr($payment->created_at,0,4).'-'.Sequence::nextval('doc_number'),
					'due_date' => date('Y-m-d H:i:s'),
					'note' => $payment->payment_method.'-'.$payment->sale.'. '.$payment->note,
					'sale' => $newSale,
					'reference' => $newReference,
					'status' => Refund::STATUS_TOPAY,
				]);
				$what = 'Refund';
			} else {
				$credit = new Credit([
					'document_type' => Credit::TYPE_CREDIT,
					'client_id' => $payment->client_id,
					'name' => substr($payment->created_at,0,4).'-'.Sequence::nextval('credit_number'),
					'due_date' => date('Y-m-d H:i:s'),
					'note' => $payment->payment_method.'-'.$payment->sale.'. '.$payment->note,
					'sale' => $newSale,
					'reference' => $newReference,
					'status' => Credit::STATUS_TOPAY,
				]);
				$what = 'Credit note';
			}
			$credit->save();
			$credit->refresh();
			$credit_item = Item::findOne(['reference' => Item::TYPE_CREDIT]);
			$creditLine = new DocumentLine([
				'document_id' => $credit->id,
				'item_id' => $credit_item->id,
				'quantity' => 1,
				'unit_price' => 0,
				'vat' => $credit_item->taux_de_tva,
				'extra_type' => DocumentLine::EXTRA_REBATE_AMOUNT,
				'extra_amount' => $payment->amount,
				'due_date' => $credit->due_date,
			]);
			$creditLine->updatePrice(); // adjust htva/tvac from extra's
			$creditLine->save();
			$credit->updatePrice();
			$credit->save();
			$payment->delete();
			Yii::$app->session->setFlash('success', Yii::t('store', $what.' created.'));
			return $this->redirect(Url::to(['/order/document/view', 'id' => $credit->id]));
		}
		Yii::$app->session->setFlash('error', Yii::t('store', 'Credit note not created.').'='.$ticket);
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionOldSystem($id) {
		if($model = Document::findDocument($id)) {
			$balance = $model->getBalance();
			$account = new Account([
				'client_id' => $model->client_id,
				'payment_method' => Payment::METHOD_OLDSYSTEM,
				'payment_date' => date('Y-m-d'),
				'amount' => $balance,
				'status' => 'CREDIT',
			]);
			$account->save();
			$account->refresh();
			$payment = new Payment([
				'sale' => $model->sale,
				'client_id' => $model->client_id,
				'payment_method' => Payment::METHOD_OLDSYSTEM,
				'amount' => $balance,
				'status' => Payment::STATUS_PAID,
				'account_id' => $account->id,
			]);
			$payment->save();
			$model->setStatus(Document::STATUS_TOPAY);
			$model->save();
            return $this->redirect(['/accnt/account/client', 'id' => $model->client_id]);
		} else
			throw new NotFoundHttpException('The requested page does not exist.');
        
	}

}
