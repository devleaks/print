<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Pdf;
use app\models\PdfSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * PdfController implements the CRUD actions for Pdf model.
 */
class PdfController extends Controller
{
	// see http://www.it.uu.se/datordrift/maskinpark/skrivare/cups/ for options
	public $print_command = 'lpr -o media=A4 -o sides=two-sided-long-edge';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Pdf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PdfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pdf model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
        return $this->redirect($model->getUrl());
    }

    /**
     * Displays a single Pdf model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisplay($fn)
    {
		$model = Pdf::findOne(['filename' => $fn]);
        return $this->redirect($model->getUrl());
    }

    /**
     * Deletes an existing Pdf model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteCascade();

        return $this->redirect(['index']);
    }

	public function actionBulkAction() {
		if(isset($_POST)) {
			if(isset($_POST['selection'])) {
				$selection = explode(',',$_POST['selection']);
				if(count($selection) > 0) {
					Yii::trace('selection: '.$_POST['selection'], 'PdfController::actionBulkAction');
					$action = $_POST['action'];
					if(in_array($action, [Pdf::ACTION_DELETE, Pdf::ACTION_PRINT])) {					
						Yii::trace('action: '.$_POST['action'], 'PdfController::actionBulkAction');
						foreach(Pdf::find()
								->andWhere(['id' => $selection])
								->each() as $pdf) {
							if($action == Pdf::ACTION_DELETE) {
								Yii::trace($pdf->id.' deleted', 'PdfController::actionBulkAction');
								$pdf->delete();
							} else if ($action == Pdf::ACTION_PRINT) {
								$cmd = YII_ENV_DEV ? 'ls ' : $this->print_command;
								$cmd .= ' '.$pdf->getFilepath();								
								system($cmd, $status);
								Yii::trace($cmd.': '.$status, 'PdfController::actionBulkAction');
							}
						}
						Yii::$app->session->setFlash('success', Yii::t('store', '{0} files {1}.', [count($selection), $action == Pdf::ACTION_PRINT ? Yii::t('store', 'printed') : Yii::t('store', 'deleted')]));
					}
				}
			}
		}
		return $this->redirect(['index', 'sort'=>'-sent_at']);
	}



    /**
     * Finds the Pdf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pdf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pdf::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
