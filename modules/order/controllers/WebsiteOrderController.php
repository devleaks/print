<?php

namespace app\modules\order\controllers;


use Yii;
use yii\web\Controller;

use app\models\WebsiteOrder;
use app\models\WebsiteOrderSearch;

class WebsiteOrderController extends Controller
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
        ];
    }

    /**
     * Lists all WebOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WebsiteOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single History model.
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
     * Finds the History model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return History the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebsiteOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionProcess($id) {
		if($model = $this->findModel($id)) {
			if($model->status == WebsiteOrder::STATUS_CREATED) {
				$model->parse_json();
				$model->createOrder();
			} else {
				$model->createOrder();
			}
			return $this->render('view', [
	            'model' => $model,
	        ]);	
		}
	}
}
