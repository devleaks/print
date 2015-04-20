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
}
