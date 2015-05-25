<?php

namespace app\modules\stats\controllers;

use app\models\Document;
use app\models\Item;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\web\Controller;

class ItemController extends Controller {

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
	                    'roles' => ['admin', 'manager'],
	                ],
	            ],
	        ],
        ];
    }


    public function actionItem() {
		$q = new Query();
		$q->select(['item.libelle_long as name', 'sum(document_line.quantity) as tot_count'])
			->from(['document', 'document_line', 'item'])
			->andwhere('document.id = document_line.document_id')
			->andwhere('document_line.item_id = item.id')
			->andWhere(['document.document_type' => Document::TYPE_ORDER])
			->groupBy('item.libelle_long')
			;

        return $this->render('item',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			])
		]);
    }


    public function actionCategory() {
		$q = new Query();
		$q->select(['item.categorie as category', 'sum(document_line.quantity) as tot_count'])
			->from(['document', 'document_line', 'item'])
			->andwhere('document.id = document_line.document_id')
			->andwhere('document_line.item_id = item.id')
			->andWhere(['document.document_type' => Document::TYPE_ORDER])
			->groupBy('item.categorie')
			;

        return $this->render('category',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			]),
		]);
    }


    public function actionYiiCategory() {
		$q = new Query();
		$q->select(['item.libelle_long as name', 'item.yii_category as category', 'sum(document_line.quantity) as tot_count'])
			->from(['document', 'document_line', 'item'])
			->andwhere('document.id = document_line.document_id')
			->andwhere('document_line.item_id = item.id')
			->andWhere(['document.document_type' => Document::TYPE_ORDER])
			->groupBy('item.libelle_long')
			;

        return $this->render('yii-category',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			]),
		]);
    }


	/**
	 * select count(document_line.item_id), round(work_width*work_height/400) surface
  from document, document_line
 where document_line.document_id = document.id
   and document.document_type = 'ORDER'
   and document_line.work_width is not null
   and document_line.work_height is not null
 group by surface
	 */
    public function actionSizes() {
		$q = new Query();
		$q->select(['item.libelle_long as name', 'item.yii_category as category', 'sum(document_line.quantity) as tot_count'])
			->from(['document', 'document_line', 'item'])
			->andwhere('document.id = document_line.document_id')
			->andwhere('document_line.item_id = item.id')
			->andWhere(['document.document_type' => Document::TYPE_ORDER])
			->groupBy('item.libelle_long')
			;

        return $this->render('yii-category2',[
			'dataProvider' => new ActiveDataProvider([
				'query' => $q
			]),
		]);
    }


}
