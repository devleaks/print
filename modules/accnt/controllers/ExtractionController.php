<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\Bill;
use app\models\Credit;
use app\models\Document;
use app\models\CaptureExtraction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ExtractionController implements the CRUD actions for Extraction model.
 */
class ExtractionController extends Controller
{
	/**
	 *  Sets global behavior for database line create/update and basic security
	 */
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
	                    'roles' => ['admin', 'compta'],
	                ],
	            ],
	        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all Extraction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new CaptureExtraction();

        if ($model->load(Yii::$app->request->post())) {
            return $this->extract($model);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Displays a single Extraction model.
     * @param integer $id
     * @return mixed
     */
    public function extract($model)
    {
		if($model->extraction_method == CaptureExtraction::METHOD_DATE) {
			$date_from = $model->date_from.' 00:00:00';
			$date_to = $model->date_to.' 23:59:59';
			Yii::trace('From '.$date_from.' to '.$date_to, 'ExtractionController::actionView');
			$docs = ($model->extraction_type) ?
					Bill::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<=','created_at',$date_to])
					:
					Credit::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<=','created_at',$date_to])	
					;
		} else { // Extraction::TYPE_REFN
			$docfrom = Document::findDocument($model->document_from);
			$docto   = Document::findDocument($model->document_to);
			$docyear = substr($docfrom->name,0,4);
			if($docyear != substr($docto->name,0,4)) {
				Yii::$app->session->setFlash('danger', 'Documents need to be from same year.');
	            return $this->render('index', [
                	'model' => $model,
            	]);
			}
			$numfrom = $docfrom->getNumberPart();
			$numto   = $docto->getNumberPart();
			$docs= [];
			Yii::trace('From '.$numfrom.' to '.$numto, 'ExtractionController::actionView');
			for($i = $numfrom; $i <= $numto; $i++)
				$docs[] = $docyear.'-'.$i;			
			$docs = ($model->extraction_type) ?
					Bill::find()->andWhere(['name' => $docs])
					:
					Credit::find()->andWhere(['name' => $docs])
					;
		}
        return $this->render('bills', [
            'dataProvider' => new ActiveDataProvider(['query'=>$docs]),
        ]);
    }


	public function actionBulkAction() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {
					$docs = Document::find()->where(['id' => $_POST['selection']]);
			        $extraction = $this->renderPartial('_extract', [
			            'models' => $docs,
			        ]);
					$badids = $this->renderPartial('_badids', [
			            'models' => $docs,
			        ]);
					$dirname = Yii::getAlias('@runtime').'/extraction/';
					if(!is_dir($dirname))
					    if(!mkdir($dirname, 0777, true)) {
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot create directory for extraction.'));
							return $this->redirect(Yii::$app->request->referrer);
					    }
					$filename = 'popsi-'.date('Y-m-d-H-i-s');
					file_put_contents($dirname.$filename.'.txt', $extraction);
					$link = Html::a($filename, Url::to(['download', 'file' => $filename]));
					Yii::$app->session->setFlash('success', Yii::t('store', 'Extracted in {file}.', ['file' => $link]));
			        return $this->renderContent($badids . '<pre>'.$extraction.'</pre>');
				}
			}
		}
		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	
	public function actionDownload($file) {
		$filename = Yii::getAlias('@runtime').'/extraction/'.$file.'.txt';
		if(file_exists($filename)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($file));
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($filename));
		    readfile($filename);
		    exit;
		}
	}


}
