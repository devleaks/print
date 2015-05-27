<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\AccountSearch;
use app\models\CaptureDate;
use app\models\PDFLetter;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class SummaryController extends Controller
{
	const ACTION_SEARCH = 'S';
	const ACTION_PRINT = 'P';
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
	                    'roles' => ['admin', 'compta', 'employee', 'manager'],
	                ],
	            ],
	        ],
        ];
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
		$capture = new CaptureDate();
        $capture->load(Yii::$app->request->post());
		Yii::trace('Date='.$capture->date);
		if(! $capture->date)
			$capture->date = date('Y-m-d', strtotime('now'));

        $searchModel = new AccountSearch();
		$searchModel->created_at = $capture->date;

		if($capture->action == self::ACTION_PRINT) {
			$pdf = new PDFLetter([
				'content'		=> $this->renderPartial('index', [
		            'searchModel' => $searchModel,
					'model' => $capture,
					'print' => true,
		        ]),
				'destination'	=> RuntimeDirectoryManager::DAILY_REPORT,
				'save'			=> true,
			]);
			$pdfDoc = $pdf->render();		
			return $this->redirect(['pdf/display', 'fn' => $pdfDoc]);
		}
		
        return $this->render('index', [
            'searchModel' => $searchModel,
			'model' => $capture,
        ]);
    }


    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionPrint($d)
    {
		$capture = new CaptureDate();
        $capture->date = $d ? $d : date('Y-m-d', strtotime('now'));

        $searchModel = new AccountSearch();
		$searchModel->created_at = $capture->date;

		$pdf = new PDFLetter([
			'content'		=> $this->renderPartial('index', [
	            'searchModel' => $searchModel,
				'model' => $capture,
				'print' => true,
	        ]),
			'destination'	=> RuntimeDirectoryManager::DAILY_REPORT,
			'save'			=> true,
		]);
		$pdfDoc = $pdf->render();		
		return $this->redirect(['pdf/display', 'fn' => $pdfDoc]);
    }


	protected function getSummary($date) {

		return $this->renderPartial('_table', [
			'priceCalculator' => $model->getPriceCalculator(),
			'print' => $print,
        ]);
	}

	

}
