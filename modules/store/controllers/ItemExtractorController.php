<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Item;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemExtractorController extends Controller
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
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
		$categories = ['CadreParam'];

		$items = Item::find()
			->select([
				"id",
				"reference",
				"yii_category",
				"libelle_long",
				"prix_de_vente",
				"taux_de_tva",
				"fournisseur"
			])
			->where(['NOT',['yii_category' => null]])
			->asArray()
			->all()
		;
		
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $items;
    }

}