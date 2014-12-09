<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Parameter;
use app\models\ParameterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParameterController implements the CRUD actions for Parameter model.
 */
class ParameterController extends Controller
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
	                    'roles' => ['admin'],
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
     * Lists all Parameter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParameterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parameter model.
     * @param string $domain
     * @param string $name
     * @return mixed
     */
    public function actionView($domain, $name)
    {
        return $this->render('view', [
            'model' => $this->findModel($domain, $name),
        ]);
    }

    /**
     * Creates a new Parameter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Parameter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'domain' => $model->domain, 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Parameter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $domain
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($domain, $name)
    {
        $model = $this->findModel($domain, $name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'domain' => $model->domain, 'name' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Parameter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $domain
     * @param string $name
     * @return mixed
     */
    public function actionDelete($domain, $name)
    {
        $this->findModel($domain, $name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Parameter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $domain
     * @param string $name
     * @return Parameter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($domain, $name)
    {
        if (($model = Parameter::findOne(['domain' => $domain, 'name' => $name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
