<?php

namespace app\modules\admin\controllers;

use app\models\Update;
use yii\web\Controller;

class UpdateController extends Controller
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
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionUpdate()
    {
		$version = isset($_POST['version']) ? $_POST['version'] : Update::LATEST;
		//Update::executeUpdate();
        return $this->render('update', ['version' => $version]);
    }

}
