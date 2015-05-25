<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Account;
use app\models\AccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class SummaryController extends Controller
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
	                    'roles' => ['admin', 'compta', 'employee', 'manager'],
	                ],
	            ],
	        ],
        ];
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
		//$this->layout = "@app/views/layouts/main2";
        $searchModel = new AccountSearch();
        $searchModel->load(Yii::$app->request->queryParams);
		if($searchModel->created_at == '')
			$searchModel->created_at = date('Y-m-d', strtotime('now'));
		
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

}
