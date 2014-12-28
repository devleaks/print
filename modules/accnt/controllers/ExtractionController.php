<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Bill;
use app\models\Credit;
use app\models\Document;
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
		if($model->extraction_method == Extraction::METHOD_DATE) {
			$date_from = $model->date_from;
			$date_to = str_replace($model->date_to, '00:00:00', '23:59:59');
			$docs = ($model->extraction_type == Extraction::TYPE_CREDIT) ?
						Credit::find()
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to])	
					:
						Bill::find()
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to]);
		} else { // Extraction::TYPE_REFN
			$docfrom = Document::findDocument($model->document_from);
			$docto   = Document::findDocument($model->document_to);
			$docyear = substr($docfrom->name,0,4);
			if($docyear != substr($docto->name,0,4)) {
				Yii::$app->session->setFlash('danger', 'Documents need to be from same year.');
	            return $this->render('create', [
                	'model' => $model,
            	]);
			}
			$numfrom = $docfrom->getNumberPart();
			$numto   = $docto->getNumberPart();
			$docs= [];
			Yii::trace('From '.$numfrom.' to '.$numto, 'ExtractionController::actionView');
			for($i = $numfrom; $i <= $numto; $i++)
				$docs[] = $docyear.'-'.$i;			
			$docs = ($model->extraction_type == Extraction::TYPE_CREDIT) ?
					Credit::find()->andWhere(['name' => $docs])
					:
					Bill::find()->andWhere(['name' => $docs]);
		}
        return $this->render('bills', [
            'dataProvider' => new ActiveDataProvider(['query'=>$docs]),
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
				if(count($_POST['selection']) > 0) {
					$docs = Document::find()->where(['id' => $_POST['selection']]);
			        $extraction = $this->renderPartial('_extract', [
			            'models' => $docs,
			        ]);
					$dirname = Yii::getAlias('@runtime').'/extraction/';
					if(!is_dir($dirname))
					    if(!mkdir($dirname, 0777, true)) {
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot create directory for extraction.'));
							return $this->redirect(Yii::$app->request->referrer);
					    }
					$filename = 'popsi-'.date('Y-m-d-H-i-s');
					file_put_contents($dirname.$filename.'.txt', $extraction);
					Yii::$app->session->setFlash('success', Yii::t('store', 'Extracted in {file}.', ['file' => $filename]));
			        return $this->renderContent('<pre>'.$extraction.'</pre>');
				}
			}
		}
		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Yii::$app->request->referrer);
	}
}
