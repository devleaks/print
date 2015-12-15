<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Bill;
use app\models\CaptureExtraction;
use app\models\Client;
use app\models\Credit;
use app\models\Document;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
			$dates = explode(' - ', $model->date_range);
			$date_from = '';
			$date_to = '';
			if(count($dates) == 2) {
				$date_from = trim($dates[0]).' 00:00:00';
				$date_to = date('Y-m-d 00:00:00', strtotime('+ 1 day', strtotime(trim($dates[1]))));
			} else {
				$date_from = substr($dates[0], 0, 10).' 00:00:00';
				$date_to = substr($dates[0], 0, 10).' 23:59:59';
				Yii::$app->session->setFlash('warning', Yii::t('store', 'There was no " - " separator between dates. I assume on date "{0}" only.', $date_from) );
			}
			Yii::trace('From '.$date_from.' to '.$date_to, 'ExtractionController::actionView');
			$docs = Bill::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<','created_at',$date_to])
					->union(
					Credit::find()
						->andWhere(['>=','created_at',$date_from])
						->andWhere(['<','created_at',$date_to])	
					);
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
			$docs = Bill::find()->andWhere(['name' => $docs])
					->union(
					Credit::find()->andWhere(['name' => $docs])
					);
		}
		$warning = clone $docs;
		if(($count = $warning->andWhere(['status' => Document::STATUS_OPEN])->count()) > 0) {
			Yii::$app->session->setFlash('warning', Yii::t('store', 'There are {0} OPEN bill(s)/credit note(s).', $count));
		}
        return $this->render('bills', [
            'dataProvider' => new ActiveDataProvider(['query'=>$docs]),
        ]);
    }


	public function actionBulkAction() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				if(count($_POST['selection']) > 0) {

					$gooddocs = $_POST['selection'];
					
					// 1. Remove doc where there is an invalid item
					$baddocs = [];
					foreach(Document::find()
									->joinWith('documentLines.item')
									->andWhere(['document.id' => $_POST['selection']])
									->andWhere(['OR', ['item.comptabilite' => ''] , ['item.comptabilite' => null]])
									->each() as $doc) {
						$key = array_search($doc->id,$gooddocs);
						if($key!==false)
						    unset($gooddocs[$key]);
						$baddocs[] = $doc;
					}

					// 2. Remove doc where there is an invalid client
					$client_ids = [];
					$badclients = [];
					$baddocscli = [];
					foreach(Document::find()->where(['id' => $gooddocs])->each() as $doc) {
						if( strpos($doc->client->comptabilite, '??') === false && !in_array($doc->client_id, $client_ids) )
							$client_ids[] = $doc->client_id;
						if( strpos($doc->client->comptabilite, '??') !== false || $doc->client->comptabilite == '') {
							$badclients[] = $doc->client_id;
							$key = array_search($doc->id,$gooddocs);
							if($key!==false)
							    unset($gooddocs[$key]);
							$baddocscli[$doc->client_id] = $doc; // bad doc because client is not valid, this only remember the LAST (if there are several) wrong document for the client
						}
					}
					
					// 2b. Adding clients created or updated in same timeframe
					$lastUpdate = date('Y-m-01 00:00:00', strtotime('now - 90 days'));					
					foreach(Client::find()->andWhere(['>', 'updated_at', $lastUpdate])->each() as $client) {
						if(! in_array($client->id, $client_ids))
							$client_ids[] = $client->id;
					}

					// 3. Export good docs
			        $extraction = $this->renderPartial('_extract', [
			            'clients' => Client::find()->where(['id' => $client_ids]),
			            'models' => Document::find()->where(['id' => $gooddocs]),
			        ]);

					// 4. Report bad clients and bad docs
					$badreport = $this->renderPartial('_bad', [
			            'clients' => Client::find()->where(['id' => $badclients])->orderBy('nom'),
			            'baddocscli' => $baddocscli,
			            'models' => $baddocs,
			        ]);

					$dirname  = RuntimeDirectoryManager::getDocumentRoot();
					$filename = RuntimeDirectoryManager::getFilename(RuntimeDirectoryManager::EXTRACTION, 'popsi');
					$data = mb_convert_encoding($extraction, 'ISO-8859-1', 'auto');
					file_put_contents($dirname.$filename, $data);

					$link = Html::a($filename, Url::to(['download', 'file' => $filename]));					
					Yii::$app->session->setFlash('success', Yii::t('store', 'Extracted in {file}.', ['file' => $link]));
			        return $this->renderContent($badreport . '<pre>'.$extraction.'</pre>');
				}
			}
		}
		Yii::$app->session->setFlash('warning', 'No document selected.');
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	
	public function actionDownload($file) {
		$filename = RuntimeDirectoryManager::getDocumentRoot().$file;
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
