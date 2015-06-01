<?php

namespace app\modules\stats\controllers;

use app\models\Document;
use app\models\DocumentLine;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class MasonryController extends Controller
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
	                    'roles' => ['admin', 'manager'],
	                ],
	            ],
	        ],
        ];
    }

    public function actionBricks()
    {
        return $this->render('bricks', ['dataProvider' => new ActiveDataProvider([
				'query' =>  DocumentLine::find()->joinWith('document')
												->andWhere(['>', 'work_width', 0])
												->andWhere(['>', 'work_height', 0])
												->andWhere(['document_type' => Document::TYPE_ORDER])
			])
		]);
    }

    public function actionFramesOld()
    {
        return $this->render('frames', ['dataProvider' => new ActiveDataProvider([
				'query' =>  DocumentLine::find()->joinWith('document')
												->andWhere(['>', 'work_width', 0])
												->andWhere(['>', 'work_height', 0])
												->andWhere(['document_type' => Document::TYPE_ORDER])
												->orderBy('work_width desc,work_height desc')
			])
		]);
    }

    public function actionFrames()
    {
		$q = (new Query())->from('document_line,document')
											->select(['tot_count' => 'sum(document_line.quantity)', 'width' => 'document_line.work_width', 'height' => 'document_line.work_height'])
											->where('document_line.document_id = document.id')
											->andWhere(['>', 'work_width', 0])
											->andWhere(['>', 'work_height', 0])
											->andWhere(['document_type' => Document::TYPE_ORDER])
											->groupBy('document_line.work_width,document_line.work_height')
											->orderBy('tot_count asc,document_line.work_width desc,document_line.work_height desc')
											;
		$c = clone $q;
		$max = 0;
		foreach($c->each() as $f)
			$max = max($max,$f['tot_count']);
        return $this->render('frames', ['dataProvider' => new ActiveDataProvider([
				'query' =>  $q
			]),
			'max' => $max
		]);
    }

/*
	create or replace view document_size as
	select quantity, work_width as largest, work_height as shortest
	  from document_line dl, document d
	 where d.id = dl.document_id
	   and d.document_type = 'ORDER'
	   and work_width is not null
	   and work_height is not null
	   and work_width >= work_height
	union
	select quantity, work_height as largest, work_width as shortest
	  from document_line dl, document d
	 where d.id = dl.document_id
	   and d.document_type = 'ORDER'
	   and work_width is not null
	   and work_height is not null
	   and work_width < work_height
	*/
    public function actionFramesStraightened() {
		$q = (new Query())->from('document_size')
											->select(['tot_count' => 'sum(quantity)', 'width' => 'largest', 'height' => 'shortest'])
											->groupBy('largest,shortest')
											->orderBy('tot_count asc,largest desc,shortest desc')
											;
		$c = clone $q;
		$max = 0;
		foreach($c->each() as $f)
			$max = max($max,$f['tot_count']);
        return $this->render('frames', ['dataProvider' => new ActiveDataProvider([
				'query' =>  $q
			]),
			'max' => $max
		]);
    }
}
