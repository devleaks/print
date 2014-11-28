<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
		if(Yii::$app->user->isGuest)
        	return $this->render('index');
		else
       		return $this->render(Yii::$app->user->identity->role != '' ? Yii::$app->user->identity->role : 'welcome');
    }

    public function actionHelp($f = null)
    {
		if(Yii::$app->user->isGuest)
        	return $this->render('index');
		else
        	return $this->render('help', ['file' => $f]);
    }

    public function actionStatus($id)
    {
		if(Yii::$app->user->isGuest)
        	return $this->render('index'); // or render minimal order status?
		else {
			$model = null; // Order::findOne($id);
       		return $this->render('status', ['model' => $model]);
		}
    }
}
