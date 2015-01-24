<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use app\models\PDFLetter;
use app\models\Parameter;
use yii\helpers\Url;
use yii\web\Controller;

class PriceController extends Controller
{
	const SHOW_STATS = false;
	
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
		$dataProvider->query->andWhere(['yii_category' => ['Cadre', 'Support', 'ChromaLuxe', 'UV']])
							->andWhere(['status' => Item::STATUS_ACTIVE]);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


	protected function getTable($id) {
		$model = $this->findModel($id);

		$wval = explode(',', Parameter::getTextValue('price_list', 'width'));
		$hval = explode(',', Parameter::getTextValue('price_list', 'height'));
		
		$content = '';

		switch(strtolower($model->yii_category)) {
			case 'chromaluxe':
				$p = Parameter::find()->andWhere(['domain' => 'formule'])
									  ->andWhere(['like', 'name', 'ChromaLuxe'])
									  ->orderBy('value_number desc')
									  ->asArray()
									  ->all();
				$w_max = Parameter::getIntegerValue('chroma_device', 'width');
				$h_max = Parameter::getIntegerValue('chroma_device', 'height');
				$max = max($w_max,$h_max);
	    		$content = $this->renderPartial('_table-chromaluxe', [
		            'model' => $model,
					'parameters' => $p,
					'min_w' => 10,
					'max_w' => $max,
					'stp_w' => 10,
					'min_h' => 10,
					'max_h' => $max,
					'stp_h' => 10,
					'w_max' => $w_max,
					'h_max' => $h_max,
					'stats' => self::SHOW_STATS
		        ]);
				break;
			case 'cadre':
				if($model->fournisseur == 'Nielsen') {
					$reg_a = $model->prix_de_vente;
					$reg_b = 0;
				} else if ($model->fournisseur == 'Exhibit') {
	    			$content = $this->renderPartial('_table-cadre', [
			            'model' => $model,
						'min_w' => $wval[0],
						'max_w' => $wval[1],
						'stp_w' => $wval[2],
						'min_h' => $hval[0],
						'max_h' => $hval[1],
						'stp_h' => $hval[2],
						'stats' => self::SHOW_STATS
			        ]);
				} else { // linear regression
					$moda = Item::findOne(['reference' => $model->reference . '_A']);
					$modb = Item::findOne(['reference' => $model->reference . '_B']);

					if(!$moda || !$modb) {
						Yii::$app->session->setFlash('warning', Yii::t('store', 'Item price is not linear.'));
			        	return $this->redirect(Url::to(['index']));
					} else {
						$reg_a = $moda->prix_de_vente;
						$reg_b = $modb->prix_de_vente;
					}
				}
    			$content = $this->renderPartial('_table-cadre', [
		            'model' => $model,
					'reg_a' => $reg_a,
					'reg_b' => $reg_b,
					'min_w' => $wval[0],
					'max_w' => $wval[1],
					'stp_w' => $wval[2],
					'min_h' => $hval[0],
					'max_h' => $hval[1],
					'stp_h' => $hval[2],
					'stats' => self::SHOW_STATS
		        ]);
				break;
			case 'support':
			case 'uv':
				$moda = Item::findOne(['reference' => $model->reference . '_A']);
				$modb = Item::findOne(['reference' => $model->reference . '_B']);
				if(!$moda || !$modb) {
					Yii::$app->session->setFlash('warning', Yii::t('store', 'Could not find parameters for {0}.', [$model->reference]));
		        	return $this->redirect(Url::to(['index']));
				}
				$content = $this->renderPartial('_table-support', [
		            'model' => $model,				
					'reg_a' => $moda->prix_de_vente,
					'reg_b' => $modb->prix_de_vente,
					'min_w' => $wval[0],
					'max_w' => $wval[1],
					'stp_w' => $wval[2],
					'min_h' => $hval[0],
					'max_h' => $hval[1],
					'stp_h' => $hval[2],
					'stats' => self::SHOW_STATS
		        ]);
				break;
		}
		
		return $content;
	}

	
    public function actionView($id) {
		return $this->render('view', ['model' => $this->findModel($id), 'content' => $this->getTable($id)]);		
    }


	public function actionPrint($id, $filename = null) {
		$pdf = new PDFLetter([
			'controller'	=> $this,
			'orientation'	=> PDFLetter::ORIENT_LANDSCAPE,
			'content'		=> $this->getTable($id),
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
