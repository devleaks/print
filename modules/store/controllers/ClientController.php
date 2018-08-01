<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Client;
use app\models\ClientSearch;
use app\models\Document;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
	                    'roles' => ['admin', 'manager', 'frontdesk', 'employee', 'compta'],
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Client models with email address.
     * @return mixed
     */
    public function actionMailing()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['not', ['email' => '']]);

        return $this->render('mailing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Client models with email address.
     * @return mixed
     */
    public function actionExtract()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('extract', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
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
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->nom = mb_strtoupper($model->nom, 'UTF-8'); // on creation only, we force uppercase
			$model->normalizeTva();
			$model->save();
			// Checks for duplicates
			$model->refresh();
			$delete_link = Html::a($model->niceName(), Url::to(['delete', 'id' => $model->id]));
			$q = Client::find()->select('soundex(nom)')->andWhere(['id' => $model->id]);
			$output = '';
			foreach(Client::find()
							->select(['nom_soundex' => 'soundex(nom)'])
							->andWhere(['nom_soundex' => $q])
							->each() as $client) {
				$output .= '» Client '.$client->niceName().'.<br/>';
			}
			if($output != '') {
				Yii::$app->session->setFlash('info', 
					'<p>'.Yii::t('store', 'The following client have similar sounding names:').'</p>'.$output.
					'<p>'.Yii::t('store', 'Delete {0}', $delete_link).'</p>'
				);
			}
			
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->normalizeTva();
			$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLiveUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->normalizeTva();
			$model->save();
			Yii::$app->session->setFlash('info', Yii::t('store', 'Client updated').'.');
        }
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNew($ret = null)
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			/*if($model->reference_interne == '') {
				$model->reference_interne = 'YII-'.$model->id;
			}*/
			$model->nom = mb_strtoupper($model->nom, 'UTF-8'); // on creation only, we force uppercase
			$model->normalizeTva();
			$model->save();

			// Checks for duplicates
			$model->refresh();
			$delete_link = Html::a($model->niceName(), Url::to(['delete', 'id' => $model->id]), [
				'title' => Yii::t('store', 'Delete client'),
        		'data-method' => 'post',
        		'data-confirm' => Yii::t('store', 'Are you sure you want to delete this client?'),
			]);
			$q = Client::find()->select('soundex(nom)')->andWhere(['id' => $model->id]);
			$output = '';
			foreach(Client::find()
							->andWhere(['soundex(nom)' => $q])
							->andWhere(['not',['id' => $model->id]])
							->each() as $client) {
				$output .= Yii::t('store', '» Client {0}',$client->niceName()).'.<br/>';
			}
			if($output != '') {
				Yii::$app->session->setFlash('info', 
					'<p>'.Yii::t('store', 'The following client have similar sounding names:').'</p>'.$output.
					'<p>'.Yii::t('store', 'Delete client {0}', $delete_link).'</p>'
				);
			}
			
			if($ret == null)
            	return $this->redirect(Url::to(['index', 'sort' => '-updated_at']));
			else if($ret == Document::TYPE_BID)
            	return $this->redirect(Url::to(['/order/document/create-bid', 'id' => $model->id]));
			else if($ret == Document::TYPE_BID)
            	return $this->redirect(Url::to(['/order/document/create-bill', 'id' => $model->id]));
			else
            	return $this->redirect(Url::to(['/order/document/create', 'id' => $model->id]));
        } else {
            return $this->render('new', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMaj($id, $type)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->normalizeTva();
			$model->save();
            return $this->redirect(Url::to(['/order/document/create', 'id' => $model->id, 'type' => $type]));
        } else {
            return $this->render('maj', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
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
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	public function actionGetUniqueIdentifier($s) {
		echo Json::encode(['result' => Client::getUniqueIdentifier($s)]);
	}
}
