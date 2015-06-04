<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\CaptureUpload;
use app\models\Document;
use app\models\BankTransaction;
use app\models\BankTransactionSearch;
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
		
		for ($i = 0; $i < count($rows); $i++) {
			if( $ignorefirstline and ($i == 0) ) continue; // skip first line
			
			$rows[$i] = trim($rows[$i]);
			if (empty($rows[$i])) {
				continue;
			}

			$details = explode($delimiter, $rows[$i]);
			$details = array_map(function($val) { return trim($val, '"');}, $details);
				
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
			$transaction->save();
		}
		Yii::$app->session->setFlash('success', Yii::t('store', '{0} transactions uploaded.', [count($rows) - 1]));
		return true;
	}

    public function actionReconsile()
    {
		$documents = [];
		foreach(BankTransaction::find()->andWhere(['status' => BankTransaction::STATUS_UPLOADED])->each() as $trans) {
			if ( preg_match_all( '/([0-9]{12})/', $trans->note, $matches ) ) {
				$document = null;
				$i = 1;
				$found = $matches[1];
				while($i < count($found) && !$document) {
					$code = substr($found[$i], 0, 3).'/'.substr($found[$i], 3, 4).'/'.substr($found[$i], 7, 5);
					$document = Document::findOne(['reference' => $code]);
					$i++;
				}
				if($document) {
					$documents[] = [
						'extract' => $trans->name,
						'bill' => $document->name,
						'bill_amount' => $document->vat_bool ? $document->price_htva : $document->price_tvac,
						'extract_amount' => $trans->amount,
						'code' => $code
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

}
