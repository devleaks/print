<?php

namespace app\modules\order\controllers;

use yii\web\Controller;

class DefaultController extends Controller
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

    public function actionIndex()
    {
        return $this->render('index');
    }
}
