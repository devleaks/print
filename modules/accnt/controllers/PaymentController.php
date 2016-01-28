<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\Cash;
use app\models\CaptureRefund;
use app\models\Document;
use app\models\DocumentLine;
use app\models\History;
use app\models\Item;
use app\models\Parameter;
use app\models\Payment;
use app\models\PaymentLink;
use app\models\PaymentSearch;
use app\models\Refund;
use app\models\Sequence;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
	/**
	 *  Sets global behavior for database line create/update and basic security
	 */
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
	                    'roles' => ['admin', 'compta', 'manager', 'frontdesk', 'employee'],
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
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Payment models for a single sale.
     * @return mixed
     */
    public function actionSale($id)
    {
		$model = $this->findDocument($id);
        $dataProvider = new ActiveDataProvider([
			'query' => $model->getPayments(),
		]);

        return $this->render('sale', [
            'dataProvider' => $dataProvider,
			'model' => $model
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndexByType()
    {
        return $this->redirect(['summary/index']);
    }

    /**
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			History::record($model, 'EDITED', 'Payment modified.', true, null);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $payment = $this->findModel($id);
		if ($doc = Document::find()->andWhere(['sale' => $payment->sale])->orderBy('created_at desc')->one()) {
			$typedDoc = Document::findDocument($doc->id);
			$typedDoc->deletePayment($payment->id);
        	return $this->redirect(['sale', 'id' => $doc->id]);
		} else
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not deleted. Document not found for sale {0}.', [$payment->sale]));
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findDocument($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionMultidoc() {
		$q = Payment::find()
				->select('account_id')
				->distinct()
				->groupBy('account_id')
				->having('count(account_id) > 1')
				;
		$dataProvider = new ActiveDataProvider([
			'query' => Account::find()->where(['id' => $q]),
		]);

        return $this->render('multidoc', [
            'dataProvider' => $dataProvider,
        ]);
	}
	
	public function actionCreditList() {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['payment.status' => Payment::STATUS_OPEN]);

        return $this->render('credit', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'capture' => new CaptureRefund(),
        ]);
    }

	public function actionRefund() {
		$capture = new CaptureRefund();
        if ($capture->load(Yii::$app->request->post()) && $capture->validate()) {
			$capture->selection = isset($_POST['selection']) ? $_POST['selection'] : [];
			if(count($capture->selection) > 0) {
				$transaction = Yii::$app->db->beginTransaction();
				$client_id = -1;
				$ok = true;
				$total = 0;
				$note = '';
				foreach(Payment::find()->andWhere(['id' => $_POST['selection']])->each() as $payment) {
					if($client_id = -1 && $ok)
						$client_id = $payment->client_id;
					else
						$ok = ($payment->client_id == $client_id);
					$total += $payment->amount;
					$note = Document::append($note, $payment->note, '; ', 160);;
					$payment->status = Payment::STATUS_PAID;
					$payment->save();
				}
				if($ok) {
					// 1. Create refund document
					$newSale = Document::nextSale();
					$newReference = Document::commStruct(date('y')*10000000 + $newSale);
					$o = Parameter::getTextValue('application', Refund::TYPE_REFUND, '-');
					$credit = new Refund([
						'document_type' => Refund::TYPE_REFUND,
						'sale' => $newSale,
						'client_id' => $payment->client_id,
						'name' => Document::generateName(Document::TYPE_REFUND),
						'due_date' => date('Y-m-d H:i:s'),
						'note' => $payment->note, // $payment->payment_method.'-'.$payment->sale.'. '.
						'reference' => $newReference,
						'status' => Refund::STATUS_CLOSED,
						'credit_bool' => true,
					]);
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
						'extra_amount' => $total,
						'due_date' => $credit->due_date,
						'note' => $note,
					]);
					$creditLine->updatePrice(); // adjust htva/tvac from extra's
					$creditLine->save();
					$credit->updatePrice();
					$credit->save();
					
					// 2. Refund credits
					$cash = null;
					if($capture->method == Payment::CASH) {
						$cash = new Cash([
							'document_id' => $credit->id,
							'sale' => $newSale,
							'amount' => -$total,
							'payment_date' => $capture->date ? $capture->date : date('Y-m-d'),
						]);
						$cash->save();
						$cash->refresh();
					}
					$refund = new Account([
						'client_id' => $client_id,
						'payment_method' => $capture->method,
						'payment_date' => date('Y-m-d H:i:s'),
						'amount' => -$total,
						'status' => (-$total > 0) ? 'CREDIT' : 'DEBIT',
						'cash_id' => $cash ? $cash->id : null,
					]);
					$refund->save();
					$refund->refresh();
					//
					$credit->addPayment($refund, -$total, $capture->method, $capture->note);
					$credit_payment = $credit->getPayments()->one();
					foreach(Payment::find()->andWhere(['id' => $_POST['selection']])->each() as $payment) {
						$link = new PaymentLink([
							'payment_id' => $credit_payment->id,
							'linked_id' => $payment->id
						]);
						$link->save();
					}

					$transaction->commit();
					Yii::$app->session->setFlash('success', Yii::t('store', 'Reimbursement created.'));
					return $this->redirect(Url::to(['/order/document/view', 'id' => $credit->id]));
				} else {
					$transaction->rollback();
					Yii::$app->session->setFlash('error', Yii::t('store', 'Credits must be for the same client.'));
				}
			} else {
				Yii::$app->session->setFlash('error', Yii::t('store', 'Please select one or more credit.'));
			}
		} else {
			Yii::$app->session->setFlash('error', Yii::t('store', 'Wrong capture data.'));
		}

	    return $this->redirect(Url::to(['/accnt/payment/credit-list']));
    }

}
