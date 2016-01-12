<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Backup;
use app\models\BackupSearch;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BackupController implements the CRUD actions for Backup model.
 */
class BackupController extends Controller
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
     * Lists all Backup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BackupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => new Backup(),
        ]);
    }

    /**
     * Displays a single Backup model.
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
     * Creates a new Backup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Backup();
		$model->load(Yii::$app->request->post());
		
		if($model->doBackup() && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('store', 'Backup completed.'));
			return $this->redirect(['index']);
		}

		Yii::$app->session->setFlash('error', Yii::t('store', 'There was an error producing the backup: {0}.', VarDumper::dumpAsString($model->errors, 4, true))); 			
		return $this->redirect(['index']);
    }

    /**
     * Updates an existing Backup model.
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
     * Deletes an existing Backup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Backup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Backup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Backup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionRestore() {
		$dsn = Yii::$app->getDb()->dsn;
		$db  = Backup::parseDSN($dsn);
		$dbname = $db['dbname'];
		$restore_dir = Yii::getAlias('@runtime');
		$dbfile = $restore_dir . '/restore/'.$dbname.'.gz';
		$mediafile = $restore_dir . '/restore/media.taz';
		return $this->render('restore', [
			'dbfile' => $dbfile,
			'mediafile' => $mediafile,
		]);
	}

	public function actionRestoreDev($id) {
		$model = $this->findModel($id);
		$fn = Yii::getAlias('@runtime') . '/backup/' . $model->filename;
		
		if(Backup::restore($fn)) {
			Yii::$app->session->setFlash('success', Yii::t('store', 'Backup restored.'));
	        $model = new Backup();
			if($model->doBackup() && $model->save()) {
				Yii::$app->session->setFlash('info', Yii::t('store', 'Backup completed.'));
			}
		}
		else
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Backup not restored.'));
		
		return $this->redirect(['/']);
	}

	public function actionDoRestore() {
		if(Backup::restore())
			Yii::$app->session->setFlash('success', Yii::t('store', 'Backup restored.'));
		else
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Backup not restored.'));
		
		return $this->redirect(['/']);
	}

}
