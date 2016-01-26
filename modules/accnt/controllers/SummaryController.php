<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\components\RuntimeDirectoryManager;
use app\models\Account;
use app\models\AccountLine;
use app\models\AccountSearch;
use app\models\CaptureDate;
use app\models\Cash;
use app\models\PDFLetter;
use app\models\Payment;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class SummaryController extends Controller
{
	const ACTION_SEARCH = 'S';
	const ACTION_PRINT  = 'P';
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
	                    'roles' => ['admin', 'manager', 'compta', 'employee'],
	                ],
	            ],
	        ],
        ];
    }


	protected function doSummary($searchModel, $print = '') {
		$ref_column = 'payment_date';
		$cash_amount = 0;
		$cash_count  = 0;
		$cashLines = [];
		$cash_start = 0;
		$solde = $cash_start;

		if($searchModel->payment_date != '') {
			$day_start = $searchModel->payment_date. ' 00:00:00';
			$day_end   = $searchModel->payment_date. ' 23:59:59';
			$cash_start = Cash::find()->andWhere(['<',$ref_column,$day_start])->sum('amount');
			$solde = $cash_start;

			foreach(Cash::find()
				->andWhere(['>=',$ref_column,$day_start])
				->andWhere(['<=',$ref_column,$day_end])->each() as $cash) {
				$solde += $cash->amount;
				$cashLines[] = new AccountLine([
					'note' => $cash->note,
					'amount' => $cash->amount,
					'date' => $cash->payment_date,
					'ref' => $cash->sale ? $cash->id : null,
					'solde' => $solde,
				]);
				$cash_amount += $cash->amount;
				$cash_count++;
			}
		} else {
			foreach(Cash::find()->each() as $cash) {
				$solde += $cash->amount;
				$cashLines[] = new AccountLine([
					'note' => $cash->note,
					'amount' => $cash->amount,
					'date' => $cash->payment_date,
					'ref' => $cash->sale ? $cash->id : null,
					'solde' => $solde,
				]);
				$cash_amount += $cash->amount;
				$cash_count++;
			}
		}
		
		$query = new Query();
		$query->from('account');
		if($searchModel->payment_date != '') {
			$day_start = $searchModel->payment_date. ' 00:00:00';
			$day_end   = $searchModel->payment_date. ' 23:59:59';
			$query->andWhere(['>=',$ref_column,$day_start])
				  ->andWhere(['<=',$ref_column,$day_end]);
		}

		$q = new Query(); // dummy query in case no data found
		$q->select([
			'payment_method' => 'concat("CASH")',
			'total_count' => 'sum(0)',
			'total_amount' => 'sum(0)',
		]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query->select(['payment_method',
								'tot_count' => 'count(id)',
								'tot_amount' => 'sum(amount)'])
			                 ->where(['not', ['payment_method' => Payment::CASH]])
							 ->groupBy(['payment_method'])
							 ->union($q)
		]);

		if($searchModel->payment_date != '') { //?
			$dataProvider->query
				->andWhere(['>=',$ref_column,$day_start])
				->andWhere(['<=',$ref_column,$day_end]);
		}

		return 	$this->renderPartial('_summary'.$print, [
					'dataProvider' => $dataProvider,
					'searchModel' => $searchModel,
					'cash_amount' => $cash_amount, 
					'cash_count'  => $cash_count
				]) 	.  $this->renderPartial('_detail-cash'.$print, [
					'dataProvider' => new ArrayDataProvider([
						'allModels' => $cashLines,
					]),
					'label' => Yii::t('store', 'Cash'),
					'cash_start' => $cash_start
				]);
	}

	protected function doDetail($searchModel, $print = '') {
		$ref_column = 'payment_date';
		$output = '';
		if($searchModel->payment_date != '') {
			$day_start = $searchModel->payment_date. ' 00:00:00';
			$day_end   = $searchModel->payment_date. ' 23:59:59';

			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				if($payment_method != Payment::CASH) {
					$dataProvider = new ActiveDataProvider([
						'query' => Account::find()
									->andWhere(['>=',$ref_column,$day_start])
									->andWhere(['<=',$ref_column,$day_end])
									->andWhere(['payment_method' => $payment_method])
					]);
					$output .= $this->renderPartial('_detail'.$print, ['dataProvider' => $dataProvider, 'method' => $payment_method, 'label' => $payment_label]);
				}
			}
		} else {			
			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				if($payment_method != Payment::CASH) {
					$dataProvider = new ActiveDataProvider([
						'query' => Account::find()
									->andWhere(['payment_method' => $payment_method])
					]);
					$output .= $this->renderPartial('_detail'.$print, ['dataProvider' => $dataProvider, 'method' => $payment_method, 'label' => $payment_label]);
				}
			}
		}
		return $output;
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
		$searchModel->payment_date = $capture->date;

		if($capture->action == self::ACTION_PRINT) {
			$pdf = new PDFLetter([
				'content'		=> $this->renderPartial('index', [
		            'summary' => $this->doSummary($searchModel, '_print'),
		        	'detail' => $this->doDetail($searchModel, '_print'),
					'model' => $capture,
					'print' => true
		        ]),
				'destination'	=> RuntimeDirectoryManager::DAILY_REPORT,
				'save'			=> true,
			]);
			$pdfDoc = $pdf->render();		
			return $this->redirect(['pdf/display', 'fn' => $pdfDoc]);
		}
		
        return $this->render('index', [
            'summary' => $this->doSummary($searchModel),
        	'detail' => $this->doDetail($searchModel),
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
		$searchModel->payment_date = $capture->date;
		
		$pdf = new PDFLetter([
			'content'		=> $this->renderPartial('index', [
	            'summary' => $this->doSummary($searchModel, '_print'),
	        	'detail' => $this->doDetail($searchModel, '_print'),
				'model' => $capture,
				'print' => true
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
