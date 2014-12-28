<?php

namespace app\modules\store\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use yii\web\Controller;
use yii\helpers\Url;

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
		$dataProvider->query->andWhere(['yii_category' => ['Cadre', 'Support']])
							->andWhere(['status' => Item::STATUS_ACTIVE]);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
		$model = $this->findModel($id);
		if($model->fournisseur == 'Nielsen') {
			$reg_a = $model->prix_de_vente;
			$reg_b = 0;
		} else { // lineor egression
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
        return $this->render('view', [
            'model' => $this->findModel($id),
			'reg_a' => $reg_a,
			'reg_b' => $reg_b,
			'use_surface' => in_array($model->yii_category, ['Support']), // otherwise, use perimeter, more common.
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
