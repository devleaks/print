<?php

namespace app\modules\stats\controllers;

use app\models\Item;
use app\models\DocumentLine;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class ItemController extends Controller
{
    public function actionCategory()
    {
		$q = DocumentLine::find()
			->select('item_id, sum(quantity) as total')
			->groupBy('item_id')->asArray()->all();

        return $this->render('category',[
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $q
			])
		]);
    }
}
