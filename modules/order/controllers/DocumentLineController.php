<?php

namespace app\modules\order\controllers;

use Yii;
use app\models\Item;
use app\models\ItemCategory;
use app\models\Document;
use app\models\DocumentLine;
use app\models\DocumentLineDetail;
use app\models\DocumentLineSearch;
use app\models\History;
use app\models\Picture;
use app\models\PDFLabel;
use app\models\Work;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DocumentLineController implements the CRUD actions for DocumentLine model.
 */
class DocumentLineController extends Controller
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
	                    'roles' => ['admin', 'manager', 'worker', 'frontdesk', 'employee', 'compta'],
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

	protected function feedback($type, $message, $cancel = null) {
		Yii::$app->session->setFlash($type, ($cancel !== null && $cancel != '') ? Yii::t('store', $message.' {0}.', $cancel) : Yii::t('store', $message));
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
			//Yii::trace($model->image_add, 'DocumentLineController::actionUpdate');
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
		if(! $doc->getDocumentLines()->exists())
			$doc->setStatus(Document::STATUS_CREATED);
        return $this->redirect(Url::to(['create', 'id' => $doc->id]));
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

			// Check for double submit
			/*
			if(!isset($_SESSION['captureclick']) || ($_POST['DocumentLine']['captureclick'] != $_SESSION['captureclick'])) {
				History::record($model, 'ERR', $model->captureclick, false, null);
				Yii::$app->session->setFlash('danger', Yii::t('store', 'There was a problem adding order line: {0}',
			 		(count($model->errors) > 0) ? VarDumper::dumpAsString($model->errors, 4, true) : Yii::t('store', 'may be form double submit?') ));
				return $this->redirect(Yii::$app->request->referrer);
			}
			unset($_SESSION['captureclick']);
			History::record($model, 'UNSET', $model->captureclick, false, null);
			*/

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
				if(!in_array($order->status, [Document::STATUS_CREATED, Document::STATUS_OPEN])) {
					if($work = $order->getWorks()->one()) {
						if($work->status != Work::STATUS_TODO)
							Yii::$app->session->setFlash('warning', Yii::t('store', 'Work has started on this order.'));
						else {
							$work_delete = Html::a(Yii::t('store', 'Click here to delete work.'),
								['/work/work/delete', 'id'=>$work->id],
								[
									'data-method' => 'post',
									'title' => Yii::t('store', 'Delete work'),
									'data-confirm' => Yii::t('store', 'Delete associated work?'),
								]);
							Yii::$app->session->setFlash('warning', Yii::t('store', 'The order has already submitted work but work has not started yet.').' '.$work_delete.' '.
									Yii::t('store', 'You must re-submit work when order is filled.'));
						}
					} else
						Yii::$app->session->setFlash('warning', Yii::t('store', 'The order was already submitted.'));
				}
					
				$order->setStatus(Document::STATUS_OPEN);
				
				$cancel = ''; /* @todo Html::a(Yii::t('store', 'Cancel'),
								['/order/document-line/delete', 'id'=>$model->id],
								[
									'data-method' => 'post',
									'title' => Yii::t('store', 'Delete order line'),
									'data-confirm' => Yii::t('store', 'Delete order line?'),
								]);*/
				$this->feedback('success', 'Line added.', $cancel);
			}
			$newDocumentLine = new DocumentLine();
			$newDocumentLine->document_id = $order->id;
            return $this->render('create', [
                'model' => $order,
                'orderLine' => $newDocumentLine,
            ]);
        } else {
			//Yii::$app->session->setFlash('danger', Yii::t('store', 'There was a problem adding a item.'));
            return $this->render('create', [
                'model' => $order,
                'orderLine' => $model,
            ]);
        }
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
		if(Yii::$app->request->isPost){
			//Yii::trace('1:'.$model->id, 'DocumentLineController::createDetail');
			$detail->document_line_id = $model->id;
			if ($detail->load(Yii::$app->request->post())) {
				//Yii::trace('2:'.$detail->document_line_id, 'DocumentLineController::createDetail');
				$free_item = Item::findOne(['reference' => '#']);			
				if($model->item_id == $free_item->id) { // copy temporary label to note, do not save "details"
					$model->note = $detail->free_item_libelle;
					$model->save();
				} else {
					// tirage_id is disabled in entry form, so it is not sent on POST, so we need to copy it from the document_line
					$items = Item::find()
								->select('id')
								->orWhere(['yii_category' => 'Tirage'])
								->orWhere(['yii_category' => 'Canvas']);
					$tirages = [];
					foreach($items->each() as $item)
						$tirages[] = $item->id;
					if(in_array($model->item_id, $tirages)) // if it is a tirage, we copy it...
						$detail->tirage_id = $model->item_id;
				 	if($detail->save()) {
						//Yii::trace('3:'.$detail->id, 'DocumentLineController::createDetail');
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
			->orWhere(['reference' => Item::TYPE_CHROMALUXE])
			->orWhere(['yii_category' => 'Tirage'])
			->orWhere(['yii_category' => 'Canvas'])
			->orWhere(['reference' => Item::TYPE_MISC]);
		$special_items  =[];
		foreach($items->each() as $item)
			$special_items[] = $item->id;
		if(! in_array($model->item_id, $special_items) ) {
			//Yii::trace('1: '.$model->item_id.' not in '.print_r($special_items, true).'.', 'DocumentLineController::createDetail');
			return false;
		}
		// we have ChromaLuxe or Fine Arts, loading detail
	    $detail = new DocumentLineDetail();
		return self::updateDetail($model, $detail);
	}

	protected static function loadImages($model) {
		if(Yii::$app->request->isPost) {
		    $uploadedFiles = UploadedFile::getInstances($model, 'image');
			$idx_offset = $model->getPictures()->count();
		    foreach($uploadedFiles as $idx => $image) {
				$nextidx = $idx_offset + $idx;
		        $picture = new Picture([
			     	'name' => $image->name,
			        'document_line_id' => $model->id,
			        'mimetype' => $image->type,
			        'filename' => $model->generateFilename($nextidx . '.' . $image->extension),
				]);

		        if($picture->save()){
		        	$imagePath = $picture->getFilepath();
					// mkdir for saved images if it does not exist
					$dirname = dirname($imagePath);
					if(!is_dir($dirname))
					    if(!mkdir($dirname, 0777, true)) {
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Cannot create directory for images {0}.', [$dirname]));
							return $this->redirect(Yii::$app->request->referrer);
					    }
		        	// Yii::trace('Filename:'.$image->tempName.' to '.$imagePath.'.', 'DocumentLineController::loadImages');
		            $image->saveAs($imagePath);
					$picture->generateThumbnail(); // also resizes image
		        } 
		    }
		}
	}


	public function actionLabel($id) {
		$model = $this->findModel($id);
		return $model->generateLabels();
	}
	
	
	/**
	 * Ajax helper functions
	 */
	public function actionItemList($search = null, $id = null) {
	    $out = ['more' => false];
	    if (!is_null($search)) {
	        $query = new Query;
	        $query->select(['id', 'text' => 'libelle_long']) // id, concat(libelle_long," //",reference) as text
	            ->from('item')
	            ->orWhere(['like', 'libelle_long', $search])
	            ->orWhere(['like', 'reference', $search])
	            ->andWhere(['OR',
					['NOT', ['yii_category' => [
						ItemCategory::CHROMALUXE_PARAM ,
						ItemCategory::FRAME_PARAM ,
						ItemCategory::RENFORT_PARAM ,
						ItemCategory::SUPPORT_PARAM ,
						ItemCategory::TIRAGE_PARAM ,
						ItemCategory::MONTAGE_PARAM ,
						ItemCategory::CORNER_PARAM ,
						ItemCategory::PROTECTION_PARAM 
					]]],
					['yii_category' => null]])
	            ->andWhere(['status' => [Item::STATUS_ACTIVE, Item::STATUS_EXTRA]])
	            ->limit(50);
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
	    return Json::encode($out);
	}

	
	public function actionItemPrice($id, $w, $h) {
		$out = [];
		if ( $item = Item::findOne($id) ) {
			if ( $pc = $item->getPriceCalculator() ) {
				$price = $pc->roundPrice($w, $h);
				$out = ['price' => $price, 'error_msg' => null];
			} else // item not found
				$out = ['price' => 0, 'error_msg' => 'Price calculator not found.'];
		} else // item not found
			$out = ['price' => 0, 'error_msg' => 'Item not found.'];
	    return Json::encode($out);
	}
	
	
}
