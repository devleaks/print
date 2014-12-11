<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Backup;
use app\models\BackupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

	protected function doBackup($model) {
		$dsn = $model->getDb()->dsn;
		$db  = Backup::parseDSN($dsn);
		$dbhost = $db['host'];
		$dbname = $db['dbname'];
		$dbuser = $model->getDb()->username;
		$dbpass = $model->getDb()->password;

		$backup_file = $dbname . date("Y-m-d-H-i-s") . '.gz';
		$backup_dir  = Yii::getAlias('@runtime') . '/backup/';
		if(!is_dir($backup_dir))
			mkdir($backup_dir);
			
		$command = "/Applications/mampstack/mysql/bin/mysqldump --opt -h $dbhost -u $dbuser -p$dbpass ".$dbname.
		           "| gzip > ". $backup_dir . $backup_file;

		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');

		if($status == 0) { // ok
			$model->filename = $backup_file;
			$model->status = 'OK';
		}
		return ($status == 0);
	}

    /**
     * Creates a new Backup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Backup();

        if (Yii::$app->request->post()) {
			if($this->doBackup($model)) {
				if($model->save()) {
					Yii::$app->session->setFlash('success', Yii::t('store', 'Backup completed.'));
 				}
			} else {
				Yii::$app->session->setFlash('error', Yii::t('store', 'There was an error producing the backup.'));
			}
  			
			return $this->redirect(['index']);
		}
		return $this->render('create', [
            'model' => $model,
        ]);
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
		$backup_dir  = Yii::getAlias('@runtime') . '/backup/';
		$backup_file = $backup_dir . $model->filename;
		if(is_file($backup_file))
			unlink($backup_file);
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
}
