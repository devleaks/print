<?php

namespace app\modules\stats\controllers;

use Yii;
use app\models\CaptureArchive;
use app\models\Document;
use app\models\DocumentArchive;
use app\models\DocumentArchiveSearch;
use app\models\Sequence;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * ArchiveController implements the CRUD actions for DocumentArchive model.
 */
class ArchiveController extends Controller
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
     * Lists all DocumentArchive models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentArchiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentArchive model.
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
     * Creates a new DocumentArchive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new DocumentArchive([
			'id' => null,
			'document_type' => Document::TYPE_ORDER,
			'name' => null,
			'sale' => Document::nextSale(),
			'client_id' => DocumentArchive::CLIENT,
			'due_date' => null,
			'price_htva' => null,
			'price_tvac' => null,
			'status' => DocumentArchive::STATUS_ACTIVE,
		]);

		$capture = new CaptureArchive();
        if ($capture->load(Yii::$app->request->post()) && $capture->validate()) {
			$model->document_type = $capture->document_type;
			$model->name = $capture->name;
			$model->due_date = $capture->due_date;
			$model->price_htva = str_replace(',','.',$capture->price_htva_virgule);
			$model->price_tvac = str_replace(',','.',$capture->price_tvac_virgule);
			$model->status = $capture->status == 1 ? DocumentArchive::STATUS_ACTIVE : DocumentArchive::STATUS_INACTIVE;
			$model->save();
			
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			$capture->status = DocumentArchive::STATUS_ACTIVE;
			if(count($capture->errors) > 0)
				Yii::$app->session->setFlash('error', Yii::t('store', 'There was an error: {0}.', VarDumper::dumpAsString($capture->errors, 4, true)));
            return $this->render('create', [
                'model' => $capture,
            ]);
        }
    }

    /**
     * Updates an existing DocumentArchive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		$capture = new CaptureArchive();
        if ($capture->load(Yii::$app->request->post()) && $capture->validate()) {
			$model->document_type = $capture->document_type;
			$model->name = $capture->name;
			$model->due_date = $capture->due_date;
			$model->price_htva = str_replace(',','.',$capture->price_htva_virgule);
			$model->price_tvac = str_replace(',','.',$capture->price_tvac_virgule);
			$model->status = $capture->status == 1 ? DocumentArchive::STATUS_ACTIVE : DocumentArchive::STATUS_INACTIVE;
			$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			$capture->id = $model->id;
			$capture->isNewRecord = false;
			$capture->document_type = $model->document_type;
			$capture->name = $model->name;
			$capture->due_date = $model->due_date;
			$capture->price_htva_virgule = str_replace('.',',',$model->price_htva);
			$capture->price_tvac_virgule = str_replace('.',',',$model->price_tvac);
			$capture->status = ($model->status == 1 || $model->status == DocumentArchive::STATUS_ACTIVE) ? DocumentArchive::STATUS_ACTIVE : DocumentArchive::STATUS_INACTIVE;
			if(count($capture->errors) > 0)
				Yii::$app->session->setFlash('error', Yii::t('store', 'There was an error: {0}.', VarDumper::dumpAsString($capture->errors, 4, true)));
            return $this->render('update', [
                'model' => $capture,
            ]);
        }
    }

    /**
     * Deletes an existing DocumentArchive model.
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
     * Finds the DocumentArchive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentArchive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentArchive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
