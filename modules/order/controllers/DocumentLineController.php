<?php

namespace app\modules\order\controllers;

use Yii;
use app\models\Item;
use app\models\Document;
use app\models\DocumentLine;
use app\models\DocumentLineDetail;
use app\models\DocumentLineSearch;
use app\models\Picture;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DocumentLineController implements the CRUD actions for DocumentLine model.
 */
class DocumentLineController extends Controller
{
    /**
     * Maximum size of images associated with ads
     * @var integer
     */
    const maxsize   = 400; // px;

    /**
     * Maximum size of thumbnail images associated with ads
     * @var integer
     */
    const thumbsize = 150; // px;

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
	                    'roles' => ['admin', 'manager', 'employee'],
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
     * Lists all DocumentLine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentLine model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing DocumentLine model.
     * If update is successful, the browser will be redirected to the 'a' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//Yii::trace('DocumentLineController::actionUpdate: '.$model->image_add);
			if($model->image_add == DocumentLine::IMAGE_REPLACE)
				$model->deletePictures();
			$this->loadImages($model);

			if($detail = $model->getDetail())
				$this->updateDetail($model, $detail);

			$model->document->updatePrice();

			$newDocumentLine = new DocumentLine();
			$newDocumentLine->document_id = $model->document_id;
            return $this->redirect(Url::to(['create', 'id' => $model->document_id]));
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DocumentLine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$doc = $model->getDocument()->one();
		$model->deleteCascade();
		$doc->updatePrice();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing DocumentLine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletePicture($id)
    {
        if( $model = Picture::findOne($id) )
			$model->deleteCascade();
		else
            throw new NotFoundHttpException('The requested page does not exist.');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the DocumentLine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentLine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentLine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new DocumentLine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
		$order = Document::findDocument($id);
        if ($order->load(Yii::$app->request->post()) && $order->save()) {
			Yii::$app->session->setFlash('info', Yii::t('store', '{document} updated', ['document' => Yii::t('store', $order->document_type)]).'.');
		}
		$order_has_rebate_before = $order->hasRebate();

		
        $model = new DocumentLine();
		if(!isset($model->document_id))
			$model->document_id = $order->id;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($model->item->reference === Item::TYPE_REBATE && $order_has_rebate_before) {
				Yii::$app->session->setFlash('info', Yii::t('store', 'You can only have one rebate line per order.'));
				$model->delete();
			} else {
				if(! $model->due_date) {
					$model->due_date = $order->due_date;
					$model->save();
				}
				if(! $model->priority) {
					$model->priority = $order->priority;
					$model->save();
				}
				$this->createDetail($model);
				$this->loadImages($model);
				$order->updatePrice();
			}
			$newDocumentLine = new DocumentLine();
			$newDocumentLine->document_id = $order->id;
            return $this->render('create', [
                'model' => $order,
                'orderLine' => $newDocumentLine,
            ]);
        } else {
			//@todo set flash with error
            return $this->render('create', [
                'model' => $order,
                'orderLine' => $model,
            ]);
        }
    }

	public function actionItemList($search = null, $id = null) {
	    $out = ['more' => false];
	    if (!is_null($search)) {
	        $query = new Query;
	        $query->select('id, libelle_long AS text') // id, concat(libelle_long," //",reference) as text
	            ->from('item')
	            ->orWhere(['like', 'libelle_long', $search])
	            ->orWhere(['like', 'reference', $search])
	            ->andWhere(['status' => [Item::STATUS_ACTIVE, Item::STATUS_EXTRA]])
	            ->limit(20);
	        $command = $query->createCommand();
	        $data = $command->queryAll();
	        $out['results'] = array_values($data);
	    }
	    elseif ($id > 0) {
	        $out['results'] = [
				'id' => $id,
				'text' => Item::findOne($id)->libelle_long,
				'item' => Item::find()->where(['id'=>$id])->asArray()->one()
			];
	    }
	    else {
	        $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
	    }
	    echo Json::encode($out);
	}
	
	/** Creates (first) order line of an order
	 *
	 */
	public static function addFirstLine($order) {
		$model = new DocumentLine();
		$model->document_id = $order->id;
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(! $model->due_date) {
				$model->due_date = $order->due_date;
				$model->save();
			}
			self::createDetail($model);
			self::loadImages($model);
			return true;
		}
		return false;
	}
	
	protected static function updateDetail($model, $detail) {
		if($_POST){
			//Yii::trace('DocumentLineController::createDetail: '.$model->id);
			$detail->document_line_id = $model->id;
			if ($detail->load(Yii::$app->request->post())) {
				//Yii::trace('DocumentLineController::createDetail: 2: '.$detail->document_line_id);
				$free_item = Item::findOne(['reference' => '#']);			
				if($model->item_id == $free_item->id) { // copy temporary label to note, do not save "details"
						$model->note = $detail->free_item_libelle;
						$model->save();
				} else {
				 	if($detail->save()) {
						//Yii::trace('DocumentLineController::createDetail: 3: '.$detail->id);
						return true;
					}
				}
			}
		}
		return false;
	}

	protected static function createDetail($model) {
		// only load detail for ChromaLuxe and Fine Arts
		$items = Item::find()
			->select('id')
			->orWhere(['reference' => '1'])
			->orWhere(['reference' => 'FineArts'])
			->orWhere(['reference' => '#'])
			->asArray()
			->all();
		$special_items  =[];
		foreach($items as $item)
			$special_items[] = $item['id'];
		if(! in_array($model->item_id, $special_items) ) {
			//Yii::trace('DocumentLineController::createDetail: 1: '.$model->item_id.' not in '.print_r($special_items, true).'.');
			return false;
		}
		// we have ChromaLuxe or Fine Arts, loading detail
	    $detail = new DocumentLineDetail();
		return self::updateDetail($model, $detail);
	}

	protected static function loadImages($model) {
		if($_POST){
		    $uploadedFiles = UploadedFile::getInstances($model, 'image');
		    //var_dump( $uploadedFiles);
		    //die();
			$dirname = $model->getPicturePath();
			if(!is_dir($dirname))
			    if(!mkdir($dirname, 0777, true)) {
			        echo 'cannot mkdir';
			        die();
			    }
			$idx_offset = $model->getPictures()->count();
		    foreach($uploadedFiles as $idx => $image) {
				$nextidx = $idx_offset + $idx;
		        $picture = new Picture();
		        $picture->name = $image->name;
		        $picture->document_line_id = $model->id;
		        $picture->mimetype = $image->type;
		        $picture->filename = $model->getFileName($nextidx . '.' . $image->extension);
		        $path = Yii::$app->params['picturePath'] . $picture->filename;
		        $thumbname = Yii::$app->params['picturePath'] . $model->getFileName($nextidx . '_t.' . $image->extension);
		        //Yii::trace('Filename:'.$image->tempName.' to '.$path.'.', 'store');

		        if($picture->save()){
		            $image->saveAs($path);
		            // make thumbnail at $thumbsize x $thumbsize max
		            $pic=Yii::$app->image->load($path);
		            //Yii::trace('Image:'.$pic->width.' X '.$pic->height.'.', 'store');
		            if($pic->width > self::thumbsize || $pic->height > self::thumbsize) {
		                $ratio = ($pic->width > $pic->height) ? $pic->width / self::thumbsize : $pic->height / self::thumbsize;
		                $newidth  = round($pic->width  / $ratio);
		                $neheight = round($pic->height / $ratio);
		                $pic->resize($newidth, $neheight);
		                $pic->save($thumbname);
		            }
		            // resize image to max $maxsize x $maxsize
		            if($pic->width > self::maxsize || $pic->height > self::maxsize) {
		                $ratio = ($pic->width > $pic->height) ? $pic->width / self::maxsize : $pic->height / self::maxsize;
		                $newidth  = $pic->width  / $ratio;
		                $neheight = $pic->height / $ratio;
		                $pic->resize($newidth, $neheight);
		                $pic->save();
		            }
		        } 
		    }
		}		
	}

}
