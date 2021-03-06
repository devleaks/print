<?php

namespace app\modules\accnt\controllers;

use app\models\CaptureUpload;
use app\models\Account;
use app\models\Payment;
use app\models\Document;
use app\models\BankTransaction;
use app\models\BankTransactionSearch;
use app\models\History;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

/**
 * BankController implements the CRUD actions for BankTransaction model.
 */
class BankController extends Controller
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
     * Lists all BankTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BankTransaction model.
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
     * Creates a new BankTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BankTransaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BankTransaction model.
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
     * Deletes an existing BankTransaction model.
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
     * Finds the BankTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpload()
    {
        $model = new CaptureUpload();

        if (Yii::$app->request->isPost) {
			$model->filename = UploadedFile::getInstance($model, 'filename');
			if ($model->filename && $model->validate()) {                
	            if ($this->upload($model)) {
					return $this->redirect(Url::to(['index', 'sort' => '-created_at']));
				}
            }
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
    }


	private function upload($model) {
		$ignorefirstline = true;
		$delimiter = ';';
		$encloser = '"';
		$endofline = "\n";
		
		if (!$contents = file_get_contents($model->filename->tempName)) {
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot open file {0}.', [$file]));
			return false;
		}

		$rows = explode($endofline, $contents);
		
		$loaded = 0;
		$rejected = 0;
		for ($i = 0; $i < count($rows); $i++) {
			if( $ignorefirstline and ($i == 0) ) continue; // skip first line
			
			$rows[$i] = trim($rows[$i]);
			if (empty($rows[$i])) {
				continue;
			}

			$details = explode($delimiter, $rows[$i]);
			$details = array_map(function($val) { return trim($val, '"');}, $details);
			
			if(! BankTransaction::find()->andWhere([
				'name' => $details[0],
				'account' => $details[7]
			])->exists()) { // checks unicity
				$transaction = new BankTransaction([
					'name' => $details[0],
					'execution_date' => \DateTime::createFromFormat('d/m/Y', $details[1])->format('Y-m-d'),
					'amount' => floatval( str_replace(',', '.', $details[3]) ),
					'currency' => $details[4],
					'source' => $details[5],
					'note' => $details[6],
					'account' => $details[7],
					'status' => BankTransaction::STATUS_UPLOADED
				]);
				if($transaction->save()) {
					$loaded++;
				}
			} else {
				$rejected++;
			}
		}
		Yii::$app->session->setFlash('success', Yii::t('store', '{0} transactions uploaded, {1} duplicates rejected.', [$loaded, $rejected]));
		return true;
	}

    public function actionReconsile()
    {
		$documents = [];
		foreach(BankTransaction::find()->andWhere(['status' => BankTransaction::STATUS_UPLOADED])->each() as $trans) {
			if ( preg_match_all( '/([0-9]{12})/', $trans->note, $matches ) ) {
				$document = null;
				$i = 0;
				$found = $matches[1];
				Yii::trace('Found '.print_r($found, true).'...', 'BankController::actionReconsile');
				while($i < count($found) && !$document) {
					$code = substr($found[$i], 0, 3).'/'.substr($found[$i], 3, 4).'/'.substr($found[$i], 7, 5);
					Yii::trace('Trying '.$code.'...', 'BankController::actionReconsile');
					$document = Document::findOne(['reference' => $code]);
					$i++;
				}
				if($document) {
					$documents[$trans->id] = [
						'extract' => $trans->name,
						'extract_amount' => $trans->amount,
						'extract_status' => $trans->status,
						'code' => $code,
						'bill' => $document->name,
						'bill_amount' => $document->getTotal(),
						'bill_due' => $document->getBalance(),
					];
				}
			}
		}

        return $this->render('reconsile', [
            'dataProvider' => new ArrayDataProvider([
				'allModels' => $documents
			]),
        ]);
    }

		public function actionMakePayments() {
			if(isset($_POST)) {
				if(isset($_POST['selection'])) {
					foreach(BankTransaction::find()
								->andWhere(['id' => $_POST['selection']])
								->andWhere(['status' => BankTransaction::STATUS_UPLOADED])->each() as $trans) {
						if ( preg_match_all( '/([0-9]{12})/', $trans->note, $matches ) ) {
							$document = null;
							$i = 0;
							$found = $matches[1];
							Yii::trace('Found '.print_r($found, true).'...', 'BankController::actionMakePayments');
							while($i < count($found) && !$document) {
								$code = substr($found[$i], 0, 3).'/'.substr($found[$i], 3, 4).'/'.substr($found[$i], 7, 5);
								Yii::trace('Trying '.$code.'...', 'BankController::actionMakePayments');
								$document = Document::findOne(['reference' => $code]);
								$i++;
							}
							if($document) {
								$ok = true;
								$transaction = Yii::$app->db->beginTransaction();
								
								$account_entered = new Account([
									'client_id' => $document->client_id,
									'payment_method' => Payment::METHOD_TRANSFER,
									'payment_date' => $trans->execution_date,
									'amount' => $trans->amount,
									'bank_transaction_id' => $trans->id,
									'status' => $trans->amount > 0 ? 'CREDIT' : 'DEBIT',
									'note' => $trans->name, // .' ('.substr($trans->note, 0, 120).')',
								]);
								$account_entered->save();
								$account_entered->refresh();
								History::record($account_entered, 'ADD', 'BankController::actionMakePayments', true, null);
								
								$document->addPayment($account_entered, $trans->amount, Payment::METHOD_TRANSFER, $trans->name);
								$trans->status = BankTransaction::STATUS_USED;
								$trans->save();
								$documents[$trans->id] = [
									'bill' => $document->name,
									'bill_amount' => $document->getTotal(),
									'bill_due' => $document->getBalance(),
									'extract' => $trans->name,
									'extract_amount' => $trans->amount,
									'extract_status' => $trans->status,
									'code' => $code,
								];
								
								if($ok) {
									Yii::$app->session->setFlash('success', Yii::t('store', 'Payment added'));
									$transaction->commit();
								} else {
									Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment was not added'));
									$transaction->rollback();
								}
									
							}
						}
					}
					Yii::$app->session->setFlash('success', Yii::t('store', 'Payment processed.'));
			        return $this->render('payment', [
			            'dataProvider' => new ArrayDataProvider([
							'allModels' => $documents
						]),
			        ]);
				}
			}

			Yii::$app->session->setFlash('warning', Yii::t('store', 'No document selected.'));
			return $this->redirect(Yii::$app->request->referrer);
		}


}
