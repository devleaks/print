<?php

namespace app\modules\stats\controllers;

use app\models\BiSale;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class BiSaleController extends ActiveController
{
	public $modelClass = 'app\models\BiSale';
	
	public function actions()
	{
	    $actions = parent::actions();
	    // disable the "delete" and "create" actions
	    unset($actions['delete'], $actions['create'], $actions['update'], $actions['options'], $actions['view']);
	    // customize the data provider preparation with the "prepareDataProvider()" method
	    $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
	    return $actions;
	}
	
	public function prepareDataProvider() {
	    return new ActiveDataProvider([
	        'query' => BiSale::find(),
	        'pagination' => false,
	    ]);
	}
}