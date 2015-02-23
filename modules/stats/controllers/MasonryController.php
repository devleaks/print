<?php

namespace app\modules\stats\controllers;

use app\models\Document;
use app\models\DocumentLine;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

class MasonryController extends Controller
{
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

    public function actionFrames()
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
}
