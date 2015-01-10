<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Parameter;
use yii\helpers\Url;
use yii\web\Controller;

class PriceController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['yii_category' => ['Cadre', 'Support', 'ChromaLuxe']])
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
        return $this->render('view-chromaluxe', [
            'model' => $this->findModel($id),
			'parameters' => $p,
			'w_max' => $w_max,
			'h_max' => $h_max,
        ]);
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
        ]);
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
