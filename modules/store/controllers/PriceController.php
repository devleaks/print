<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Parameter;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use yii\web\Controller;

class PriceController extends Controller
{
	const SHOW_STATS = false;
	
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

    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['yii_category' => ['Cadre', 'Support', 'ChromaLuxe', 'UV']])
							->andWhere(['status' => Item::STATUS_ACTIVE]);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewChromaluxe($id)
    {
		$model = $this->findModel($id);
		$p = Parameter::find()->andWhere(['domain' => 'formule'])
							  ->andWhere(['like', 'name', 'ChromaLuxe'])
							  ->orderBy('value_number desc')
							  ->asArray()
							  ->all();
		$w_max = Parameter::getIntegerValue('chroma_device', 'width');
		$h_max = Parameter::getIntegerValue('chroma_device', 'height');
		$max = max($w_max,$h_max);
        return $this->render('view-chromaluxe', [
            'model' => $this->findModel($id),
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
    }

    public function actionViewUv($id)
    {
		return $this->actionViewSupport($id);
	}

    public function actionViewSupport($id)
    {
		$model = $this->findModel($id);
		$moda = Item::findOne(['reference' => $model->reference . '_A']);
		$modb = Item::findOne(['reference' => $model->reference . '_B']);
		$wval = explode(',', Parameter::getTextValue('price_list', 'width'));
		$hval = explode(',', Parameter::getTextValue('price_list', 'height'));

		if(!$moda || !$modb) {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'Could not find parameters for {0}.', [$model->reference]));
        	return $this->redirect(Url::to(['index']));
		}
        return $this->render('view-support', [
            'model' => $this->findModel($id),
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
    }

    public function actionViewCadre($id) {
		$model = $this->findModel($id);
		$wval = explode(',', Parameter::getTextValue('price_list', 'width'));
		$hval = explode(',', Parameter::getTextValue('price_list', 'height'));
		if($model->fournisseur == 'Nielsen') {
			$reg_a = $model->prix_de_vente;
			$reg_b = 0;
		} else if ($model->fournisseur == 'Exhibit') {
	        return $this->render('view-cadre', [
	            'model' => $this->findModel($id),
				'min_w' => $wval[0],
				'max_w' => $wval[1],
				'stp_w' => $wval[2],
				'min_h' => $hval[0],
				'max_h' => $hval[1],
				'stp_h' => $hval[2],
				'stats' => self::SHOW_STATS
	        ]);
		} else { // lineor regression
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
        return $this->render('view-cadre', [
            'model' => $this->findModel($id),
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
    }


	public function actionPrint($id, $filename = null) {
		$model = $this->findModel($id);
	    $header  = $this->renderPartial('_print_header');
	    $footer  = $this->renderPartial('_print_footer');
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


		$pdfData = [
	        // set to use core fonts only
	        'mode' => Pdf::MODE_CORE, 
	        // A4 paper format
	        'format' => Pdf::FORMAT_A4, 
	        // portrait orientation
	        'orientation' => Pdf::ORIENT_LANDSCAPE, 
	        // stream to browser inline
	        'destination' => Pdf::DEST_BROWSER,
	        // your html content input
	        'content' => $content,  
	        // format content from your own css file if needed or use the
	        // enhanced bootstrap css built by Krajee for mPDF formatting 
	        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
	        // any css to be embedded if required
			'cssInline' => '.kv-wrap{padding:20px;}' .
	        	'.kv-heading-1{font-size:18px}'.
                '.kv-align-center{text-align:center;}' .
                '.kv-align-left{text-align:left;}' .
                '.kv-align-right{text-align:right;}' .
                '.kv-align-top{vertical-align:top!important;}' .
                '.kv-align-bottom{vertical-align:bottom!important;}' .
                '.kv-align-middle{vertical-align:middle!important;}' .
                '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}' .
                'table{font-size:0.8em;}'
				,
	         // set mPDF properties on the fly
			'marginHeader' => 10,
			'marginFooter' => 10,
			'marginTop' => 35,
			'marginBottom' => 35,
			'options' => [],
	         // call mPDF methods on the fly
	        'methods' => [ 
	        //    'SetHeader'=>['Laboratoire JJ Micheli'], 
	            'SetHTMLHeader'=> $header,
	            'SetHTMLFooter'=> $footer,
	        ]
		];

		if($filename) {
			$pdfData['destination'] = Pdf::DEST_FILE;
			$pdfData['filename'] = $filename;
		} else {
			$pdfData['destination'] = Pdf::DEST_BROWSER;
		}

    	$pdf = new Pdf($pdfData);
		return $pdf->render();
	}

    protected function findModel($id)
    {
        if (($model = Item::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
