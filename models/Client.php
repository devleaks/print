<?php

namespace app\models;

use Yii;
use app\components\VATValidator;
use kartik\helpers\Html as KHtml;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "client".
 */
class Client extends _Client
{
	const COMPTOIR = 'CCC';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			[['numero_tva'], VATValidator::className()],
        	[['email'], 'email'],
		]);
	}
	

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'numero_tva' => Yii::t('store', 'Numero TVA'),
            'assujetti_tva' => Yii::t('store', 'NON Assujetti à la TVA'),
        ]);
    }
	

	public static function auComptoir() {
		return Client::findOne(['comptabilite' => Client::COMPTOIR]);
	}


	public function isComptoir() {
		if($comptoir = Client::auComptoir())
			return $this->id == $comptoir->id;
		return false;
	}


    /**
     * create client name label for display
	 */
	public function niceName($show_autre = false) {
		return ucwords(mb_strtolower(
			$this->prenom.' '.$this->nom.($show_autre ? ' - '.$this->autre_nom : '')
		, 'UTF-8'));
	}

    /**
     * create client name label for display
	 */
	public function niceAltName($show_full = false) {
		return $this->autre_nom.($show_full ? ' - '.$this->prenom.' '.$this->nom : '');
	}
	
	public function sanitizeName() {
		return preg_replace('/[^a-z0-9\.]/', '', strtolower($this->nom));
	}
	
	
	public static function getUniqueIdentifier( $name ) {
		$maxlen = 9;
		$change = 0;
		$cnt = 0;
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$max = strlen($codeAlphabet);

		$try = substr(preg_replace('/[^A-Z0-9\.]/', '', strtoupper($name)), 0, $maxlen);
		while(Client::find()->where(['comptabilite' => $try])->exists()) {
			$idx = $cnt % $max;
			if($idx == 0)
				$change++;
			$try = substr($try, 0, $maxlen - $change) . substr($codeAlphabet, $idx, $change);
		}
		return strtoupper($try);
	}

    /**
     * create client label for on screen display
	 */
    public function makeAddress($upd_link = false, $type = Document::TYPE_ORDER)
    {
	 	$addr  = '<address>';
		$addr .= $this->adresse;
		$addr .= '<br>'.$this->code_postal.' '.$this->localite;
		if($this->pays != '' && !in_array(strtolower($this->pays), ['belgique','belgie','belgium'])) $addr .= '<br>'.$this->pays;
		$addr .= '<br><br><abbr title="Phone"><i class="glyphicon glyphicon-home"></i></abbr>'.' '.($this->bureau ? $this->bureau : Yii::t('store', 'No phone.'));
		$addr .= '<br><abbr title="Phone"><i class="glyphicon glyphicon-phone"></i></abbr>'.' '.($this->gsm ? $this->gsm : Yii::t('store', 'No mobile phone.').' '.Yii::t('store', 'No SMS.'));
		$addr .= '<br><abbr title="Email"><i class="glyphicon glyphicon-envelope"></i></abbr>'.' '.($this->email ? Html::mailto($this->email) : Yii::t('store', 'No email.'));
		$addr .= '<br><br><abbr title="VAT"><i class="glyphicon glyphicon-briefcase"></i></abbr>'.' '.($this->numero_tva ? $this->numero_tva : Yii::t('store', 'No VAT.'));
		if($this->commentaires)
			$addr .= '<br><br><abbr title="Note"><i class="glyphicon glyphicon-paperclip"></i></abbr>'.' '.$this->commentaires;
		$addr .= '</address>';
//		return KHtml::well($addr, KHtml::SIZE_TINY);
		return KHtml::panel([
		        'heading' => $this->prenom.' '.$this->nom.($this->autre_nom ? ' - '.$this->autre_nom : '')
						.($upd_link ?
						Html::a('<i class="glyphicon glyphicon-pencil pull-right"></i>', Url::to(['/store/client/maj', 'id' => $this->id, 'type' => $type]))
						: ''),
			    'headingTitle' => true,
		        'body' => $addr,
			]
		);
	}

    /**
     * create client label for on screen display
	 */
    public function getAddress()
    {
       	$addr  = $this->prenom.' '.$this->nom;
		$addr .= '<br>'.$this->autre_nom;
		$addr .= '<br>'.$this->adresse;
		$addr .= '<br>'.$this->code_postal.' '.$this->localite;
		if($this->pays != '' && !in_array(strtolower($this->pays), ['belgique','belgie','belgium'])) $addr .= '<br>'.$this->pays;
		$addr .= '<br>';
		$addr .= ($this->bureau ? '<br>Bureau: '.$this->bureau : '');
		$addr .= ($this->gsm ? '<br>Mobile: '.$this->gsm : '');
		$addr .= ($this->email ? '<br>e-Mail: '.$this->email : '');
		$addr .= ($this->numero_tva ? '<br><br>TVA: '.$this->numero_tva : '');
		return $addr;
	}
	

	public function isBelgian() {
		return in_array(strtolower($this->pays), ['belgique','belgie','belgium']);
	}
	

	protected function refundPullDown($id) {
		return '<div class="btn-group"><button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'.
			        	Yii::t('store', 'Refund'). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
						'<li>'.Html::a(Yii::t('store', 'Credit Note'), ['refund', 'id' => $id, 'ticket' => 0], ['title' => Yii::t('store', 'Refund with a credit note')]).'</li>'.
						'<li>'.Html::a(Yii::t('store', 'Refund'),      ['refund', 'id' => $id, 'ticket' => 1],  ['title' => Yii::t('store', 'Refund')]).'</li>'.
					'</ul></div>';
	}
	

	public function getAccountLines() {
		$accountLines = [];
		$sales = [];
		$sales_already_processed = [];
		$accounts_already_processed = [];
		
		/** Sales */

		$distinct_sales = Document::find()
					->select('sale')
					->andWhere(['client_id' => $this->id])
					->andWhere(['not', ['status' => [
						Document::STATUS_OPEN,  
						Document::STATUS_CANCELLED,  
					]]])
					->distinct();
		$documents = Document::find()->where(['sale' => $distinct_sales]);
		
		foreach($documents->each() as $doc) {
			$document = Document::findDocument($doc->id); // get proper type to call proper functions (getBalance is not the same for orders or bills from boms)
			if( ! in_array($document->sale, $sales_already_processed) ) {
				Yii::trace('DOING SALE:'.$document->sale.'.', 'Client::getAccountLines');
				$bal = $document->getBalance();
				$color = ($bal <= 0) ? 'success' : 'warning';
				//Yii::trace($document->document_type.':'.$document->name.'='.$bal, 'Client::getAccountLines');
				$old_system = '';
				if($document->getBalance() > 0) {
					$old_system = ' '.Html::a('<span class="label label-oldsystem"><i class="glyphicon glyphicon-warning-sign"></span>',
												Url::to(['/accnt/account/old-system', 'id' => $document->id]),
												[
													'title' => Yii::t('store', "Paiement par l'ancien système"),
							                        'data-confirm' => Yii::t('store', "Confirmer le paiement par l'ancien système?")
												]);
				}
				switch($document->document_type) {
					case Document::TYPE_TICKET:
					case Document::TYPE_BILL: // we always add bills and tickets, they should always get paid
						$accountLines[] = new AccountLine([
							'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])).$old_system,
							'amount' => - $document->getTotal(),
							'date' => $document->created_at,
							'ref' => $document->id,
						]);
						$sales_already_processed[] = $document->sale;
						break;
					case Document::TYPE_ORDER:
						if(! Order::findOne($document->id)->getBill()) { // if there is a bill for this order, we ignore the order, the bill will be added
							$accountLines[] = new AccountLine([
								'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])).$old_system,
								'amount' => - $document->getTotal(),
								'date' => $document->created_at,
								'ref' => $document->id,
							]);
							$sales_already_processed[] = $document->sale;
						}
						break;
					case Document::TYPE_CREDIT:
						$color = ($bal < 0) ? 'warning' : 'success';
						$accountLines[] = new AccountLine([
							'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
							'amount' => - $document->getTotal(),
							'date' => $document->created_at,
							'ref' => $document->id,
						]);
						$sales_already_processed[] = $document->sale;
						break;
					case Document::TYPE_REFUND:
						$color = ($bal < 0) ? 'warning' : 'success';
						if($document->credit_bool) {
							if($payment = $document->getPayments()->one()) {
								if($account = $payment->getAccount()->one()) {
									$note = ($account->note ? $account->note : '<span class="label label-info">'.$account->payment_method.'</span>');
									$accountLines[] = new AccountLine([
										'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])).' / '.$note,
										'amount' => $account->amount,
										'date' => $document->created_at,
										'ref' => $document->id,
									]);
									$accounts_already_processed[] = $account->id;
								}
							} else {
								$accountLines[] = new AccountLine([
									'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
									'amount' => 0, // We display balance; not total.
									'date' => $document->created_at,
									'ref' => $document->id,
								]);
							}
						} else {
							$accountLines[] = new AccountLine([
								'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
								'amount' => - $document->getTotal(),
								'date' => $document->created_at,
								'ref' => $document->id,
							]);
						}
						$sales_already_processed[] = $document->sale;
						break;
					case Document::TYPE_BID:
					default: // no money involved. Ignore. If a order or a ticket was added from this bid it will show.
						//Yii::trace($document->document_type.':'.$document->name.' IGNORED', 'Client::getAccountLines');
						break;
				}
			}
		}
		
		
		/** Payments */
		foreach($this->getAccounts()->andWhere(['not',['id'=>$accounts_already_processed]])->each() as $account) {
			//$reimburse = Html::a('<span class="label label-primary">'.Yii::t('store', 'Make credit note').'</span>', Url::to(['refund', 'id' => $account->id]), ['title' => Yii::t('store', 'Make credit note').'-'.$account->sale]);
			//$reimburse = $this->refundPullDown($account->id);
			$color = $account->payment_method == Payment::METHOD_OLDSYSTEM ? 'oldsystem' : 'info';
			$note = ($account->note ? $account->note : '<span class="label label-'.$color.'">'.$account->payment_method.'</span>');
			$accountLines[] = new AccountLine([
				'note' => /*'P '.*/$note,
				'amount' => $account->amount,
				'date' => $account->created_at,
				'ref' => $account->id,
			]);
		}	
		
		uasort($accountLines, function($a, $b) { return $a->date > $b->date; }); //wow
		
		// build summary column. No other method.
		$tot = 0;
		foreach($accountLines as $l)
			$l->account = ($tot += $l->amount);
		
		return $accountLines;
	}
	
	public function getBottomLine() {
		$accountLines = $this->getAccountLines();
		return !empty($accountLines) ? end($accountLines)->account : 0;
	}
	
	/**
	 *	Client has credit under 3 forms:
	 *		1. Credit notes with money left on them.
	 *		2. Reimbursement/refund notes.
	 *		3. Unprocessed reimbursements. (excess payment)
	 *
	 * @return CreditLine[] Credit available.
	 */
	public function getCreditLines($exclude = null) {
		$creditLines = [];
		// Credit notes still open
		$credits = Credit::find()->andWhere(['client_id'=>$this->id])->andWhere(['status' => [Credit::STATUS_TOPAY, Credit::STATUS_CLOSED]]);
		if($exclude)
			$credits->andWhere(['not', ['id' => $exclude]]);
			
		foreach($credits->each() as $document) {
			$balance = $document->getBalance();
			if($balance < -Document::PAYMENT_LIMIT)
				$creditLines[] = new CreditLine([
					'source' => CreditLine::SOURCE_CREDIT,
					'note' => $document->name,
					'date' => $document->created_at,
					'amount' => $balance,
					'ref' => $document->id,
				]);
		}
		// Credit notes still open
		$refunds = Refund::find()->andWhere(['client_id'=>$this->id])->andWhere(['status' => Credit::STATUS_TOPAY]);
		if($exclude)
			$refunds->andWhere(['not', ['id' => $exclude]]);
			
		foreach($refunds->each() as $document) {
			$creditLines[] = new CreditLine([
				'source' => CreditLine::SOURCE_REFUND,
				'note' => $document->name,
				'date' => $document->created_at,
				'amount' => $document->getBalance(),
				'ref' => $document->id,
			]);
		}
		// Unprocessed reimbursements
		/* These are the outstanding "excess" payments */
		foreach($this->getPayments()->andWhere(['status' => Payment::STATUS_OPEN])->each() as $payment) {
			$creditLines[] = new CreditLine([
				'source' => CreditLine::SOURCE_ACCOUNT,
				'note' => $payment->note,
				'date' => $payment->created_at,
				'amount' => - $payment->amount,
				'ref' => $payment->id,
			]);
		}
		return $creditLines;
	}
}
