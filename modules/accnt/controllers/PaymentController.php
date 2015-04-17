<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\Cash;
use app\models\Document;
use app\models\Payment;
use app\models\PaymentSearch;
use app\models\Sequence;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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
	                    'roles' => ['admin', 'compta', 'employee'],
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
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndexByType()
    {
        $searchModel = new PaymentSearch();
        $searchModel->load(Yii::$app->request->queryParams);
		if($searchModel->created_at == '')
			$searchModel->created_at = date('Y-m-d', strtotime('now'));
		
        return $this->render('index-by-type', [
            'searchModel' => $searchModel,
        ]);
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
		$sale = $payment->sale;
		if ($doc = Document::find()->andWhere(['sale' => $sale])->orderBy('created_at desc')->one()) {
			$typedDoc = Document::findDocument($doc->id);
			if($payment->payment_method == Payment::CASH) {
				if($cash = Cash::find()->andWhere(['sale' => $payment->sale, 'amount' => $payment->amount])->one()) {
					$cash_date = $cash->created_at;
					$cash->delete();
					$payment->delete();
					$typedDoc->setStatus(Document::STATUS_TOPAY);
					Yii::$app->session->setFlash('info', Yii::t('store', 'Cash payment deleted. {0} updated. You must review cash balance for {1}.',
								[$typedDoc->name, Yii::$app->formatter->asDate($cash_date)]));
				} else {
					Yii::$app->session->setFlash('danger', Yii::t('store', 'Cash payment not deleted because cash entry was not found.'));
				}
			} elseif($payment->payment_method == Payment::USE_CREDIT) { // used credit, we have to place the credit back
				$credit_amount = $payment->amount;
				// OPEN TRANSACTION
				$payment->delete();
				$credit = new Payment([
					'sale' => Sequence::nextval('sale'),
					'client_id' => $payment->client_id,
					'payment_method' => Payment::USE_CREDIT,
					'amount' => $credit_amount,
					'note' => Yii::t('store', 'Credit payment cancelled.'),
					'status' => Payment::STATUS_OPEN,
				]);
				$credit->save();
				$typedDoc->setStatus(Document::STATUS_TOPAY);
				// CLOSE TRANSACTION
				Yii::$app->session->setFlash('info', Yii::t('store', 'Payment with credit deleted. {0} updated. Credit amount {0}€ restored.',
							[$credit_amount]));
			} else {
				if($account = Account::find()->andWhere(['sale' => $payment->sale, 'client_id' => $payment->client_id, 'amount' => $payment->amount])->one()) {
					$account->delete();
					$payment->delete();
					$typedDoc->setStatus(Document::STATUS_TOPAY);
					Yii::$app->session->setFlash('info', Yii::t('store', 'Payment deleted. {0} updated.', [$typedDoc->name]));
				} else {
					Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not deleted because account entry was not found.'));
				}

			}
		} else
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not deleted. Document not found for sale {0}.', [$sale]));
        return $this->redirect(['index', 'sort' => '-created_at']);
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
}
