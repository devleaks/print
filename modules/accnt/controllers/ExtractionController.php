<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Bill;
use app\models\Extraction;
use app\models\ExtractionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * ExtractionController implements the CRUD actions for Extraction model.
 */
class ExtractionController extends Controller
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
     * Lists all Extraction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtractionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Extraction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		if($model->extraction_type == Extraction::TYPE_DATE) {
			$date_from = $model->date_from;
			$date_to = str_replace($model->date_to, '00:00:00', '23:59:59');
			$bills = Bill::find()
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to]);
		} else { // Extraction::TYPE_REFN
			Yii::$app->session->setFlash('warning', Yii::t('store', 'Function is not available yet.'));
			$bills = Bill::find()
							->andWhere(['>=','id',$model->document_from])
							->andWhere(['<=','id',$model->document_to]);
		}
        return $this->render('bills', [
            'dataProvider' => new ActiveDataProvider(['query'=>$bills]),
        ]);
    }

    /**
     * Creates a new Extraction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Extraction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Extraction model.
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
     * Deletes an existing Extraction model.
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
     * Finds the Extraction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Extraction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Extraction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	public function actionBulkAction() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				$bills = Bill::find()->where(['id' => $_POST['selection']]);
		        return $this->render('extract', [
		            'bills' => Bill::find()->where(['id' => $_POST['selection']]),
		        ]);
			}
		}
	}
}
