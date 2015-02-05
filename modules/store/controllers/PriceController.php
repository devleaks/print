<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\ItemCategory;
use app\models\PriceCalculator;
use app\models\NielsenPriceCalculator;
use app\models\ExhibitPriceCalculator;
use app\models\ChromaLuxePriceCalculator;
use app\models\Item;
use app\models\ItemSearch;
use app\models\PDFLetter;
use app\models\Parameter;
use yii\helpers\Url;
use yii\web\Controller;

class PriceController extends Controller
{
    public function behaviors() {
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


    public function actionIndex() {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['yii_category' => [	ItemCategory::CHROMALUXE,
															ItemCategory::RENFORT,
															ItemCategory::SUPPORT,
															ItemCategory::FRAME,
															ItemCategory::UV,
															ItemCategory::MONTAGE,
						 ]])->andWhere(['status' => Item::STATUS_ACTIVE]);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


	protected function getTable($id, $print = false) {
		$model = $this->findModel($id);

		return $this->renderPartial('_table', [
			'priceCalculator' => $model->getPriceCalculator(),
			'print' => $print,
        ]);
	}

	
    public function actionView($id) {
		return $this->render('view', ['model' => $this->findModel($id), 'content' => $this->getTable($id)]);		
    }


	public function actionPrint($id, $filename = null) {
		$pdf = new PDFLetter([
			'controller'	=> $this,
			'orientation'	=> PDFLetter::ORIENT_LANDSCAPE,
			'content'		=> $this->getTable($id, true),
			'filename'		=> $filename,
		]);
		$pdfDoc = $pdf->render();		
		return $filename ? $filename : $pdfDoc;
	}


    protected function findModel($id) {
        if (($model = Item::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
