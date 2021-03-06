<?php

namespace app\modules\work\controllers;

use Yii;
use app\models\Document;
use app\models\DocumentLine;
use app\models\Work;
use app\models\WorkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * WorkController implements the CRUD actions for Work model.
 */
class WorkController extends Controller
{
    /**
     * @inheritdoc
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
	                    'roles' => ['admin', 'manager', 'frontdesk', 'employee', 'worker', 'compta'],
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
     * @inheritdoc
     */
	public function init() {
		$this->layout = "@app/views/layouts/main-fluid";
		return parent::init();
	}

    /**
     * Lists all Work models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Work model.
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
     * Displays a single Work model.
     * @param integer $id
     * @return mixed
     */
    public function actionLine($id)
    {
		$ol = DocumentLine::findOne($id);
		$work = $ol->getDocument()->one()->getWorks()->one();
		
        return $this->render('view', [
            'model' => $work, // $this->findModel($work->id),
			'order_line' => $ol
        ]);
    }

    /**
     * Creates a new Work model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Work();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Work model.
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
     * Deletes an existing Work model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $work = $this->findModel($id);
		$doc_id = $work->document_id;
		$work->deleteCascade();

        return $this->redirect(['/order/document/view', 'id' => $doc_id]);
    }

    /**
     * Finds the Work model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Work the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Work::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all Work models for given date.
     * @return mixed
     */
    public function actionList($id = 0)
    {
		$where = Document::getDateClause(intval($id), 'work');
		Yii::trace($where, 'WorkController::actionList');
	
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//@hack
		$dataProvider->query->andWhere($where);

/*        $dataProvider = new ActiveDataProvider([
			'query' => Work::find()->andWhere($where)
		]);*/

        return $this->render('list-by-date', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'day' => $id,
        ]);
    }

	/**
	 *	Terminate all tasks of a work.
	 */
	public function actionTerminate($id) {
		$model = $this->findModel($id);
		$model->terminate(); // should only be one, at most
		$document = $model->getDocument()->one();
		$document->refresh();
        return $this->render('../../../order/views/document/view', [
            'model' => $document,
        ]);
	}


}
