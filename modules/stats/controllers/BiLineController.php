<?php

namespace app\modules\stats\controllers;

use app\models\BiLine;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class BiLineController extends ActiveController
{
	public $modelClass = 'app\models\BiLine';
	
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
	        'query' => BiLine::find(), // ->where(['between','created_at','2017-01-01','2018-01-01']),
	        'pagination' => false,
	    ]);
	}
}