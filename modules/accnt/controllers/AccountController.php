<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Client;
use app\models\Payment;
use app\models\Credit;
use app\models\Order;
use app\models\Document;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;

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
        $model = new Account();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

}
