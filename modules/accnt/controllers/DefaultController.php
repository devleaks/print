<?php

namespace app\modules\accnt\controllers;

use app\models\Client;
use app\models\Document;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

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
	                    'roles' => ['admin', 'compta'],
	                ],
	            ],
	        ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionControl()
    {
		$nopopsyclinum = $this->getNopopsyclinum()->count();
        return $this->render('control', [
			'nopopsyclinum' => $nopopsyclinum
		]);
    }

	public function getNopopsyclinum() {
		return Client::find()->leftJoin('document', 'client.id = document.client_id')
						->andWhere(['document.document_type' => Document::TYPE_BILL])
						->andWhere(['client.comptabilite' => ''])
						->union(
							Client::find()->leftJoin('document', 'client.id = document.client_id')
							->andWhere(['document.document_type' => Document::TYPE_BILL])
							->andWhere(['client.comptabilite' => null])
						);
	}

	public function actionNopopsyclinum() {
		$query = $this->getNopopsyclinum();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		return $this->render('nopopsyclinum', ['dataProvider' => $dataProvider]);
	}
}
