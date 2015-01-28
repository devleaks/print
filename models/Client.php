<?php

namespace app\models;

use Yii;
use kartik\helpers\Html as KHtml;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "client".
 */
class Client extends _Client
{
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
     * create client name label for display
	 */
	public function niceName($show_autre = false) {
		return ucwords(strtolower(
			$this->prenom.' '.$this->nom.($show_autre ? ' - '.$this->autre_nom : '')
		));
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
		return $try;
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
		
		/** All bills */
		foreach(Bill::find()->andWhere(['client_id'=>$this->id])->andWhere(['!=', 'status', Bill::STATUS_OPEN])->each() as $document) {
			$color = ($document->getBalance() <= 0) ? 'success' : 'warning';
			$accountLines[] = new AccountLine([
				'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
				'amount' => - $document->getTotal(),
				'date' => $document->created_at,
				'ref' => $document->id,
			]);
			$sales[] = $document->sale;
		}
		
		/** All orders not currently billed
		$q = Order::find()->andWhere(['client_id'=>$client->id])->andWhere(['!=', 'status', Order::STATUS_OPEN]);
		if($sales)
			$q->andWhere(['not', ['sale' => $sales]]);			
		foreach($q->each() as $document) {
			$color = ($document->getBalance() <= 0) ? 'success' : 'warning';
			$accountLines[] = new AccountLine([
				'note' => /*'B '.* /<<<<<Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
				'amount' => - $document->getTotal(),
				'date' => $document->created_at,
				'ref' => $document->id,
			]);
			$sales[] = $document->sale;
		}
		 */

		foreach(Credit::find()->andWhere(['client_id'=>$this->id])->andWhere(['!=', 'status', Credit::STATUS_OPEN])->each() as $document) {
			$color = ($document->getBalance() >= 0) ? 'success' : 'info';
			$accountLines[] = new AccountLine([
				'note' => /*'C '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
				'amount' => - $document->getTotal(),
				'date' => $document->created_at,
				'ref' => $document->id,
			]);
			$sales[] = $document->sale;
		}

		foreach($this->getPayments()->andWhere(['sale' => $sales])->each() as $payment) {
			$doc = $payment->getDocument()->one();
			if($doc) {
				if($doc->document_type == $doc::TYPE_CREDIT)
					$color = ($doc->getBalance() >= 0) ? 'success' : 'info';
				else
					$color = ($doc->getBalance() <= 0) ? 'success' : 'warning';
			} else
				$color = 'info';
				
			$note = $doc ? Html::a('<span class="label label-'.$color.'">'.$doc->name.'</span>', Url::to(['/order/document/view', 'id' => $doc->id])).' - '.$payment->getPaymentMethod()
			             : ($payment->note ? $payment->note : '<span class="label label-'.$color.'">'.$payment->payment_method.'</span>'.' - '.$payment->sale);
			$accountLines[] = new AccountLine([
				'note' => /*'P '.*/$note,
				'amount' => $payment->amount,
				'date' => $payment->created_at,
				'ref' => $payment->id,
			]);
		}

		/* These are the outstanding "excess" payments */
		foreach($this->getPayments()->andWhere(['status' => Payment::STATUS_OPEN])->each() as $payment) {
			//$reimburse = Html::a('<span class="label label-primary">'.Yii::t('store', 'Make credit note').'</span>', Url::to(['refund', 'id' => $payment->id]), ['title' => Yii::t('store', 'Make credit note').'-'.$payment->sale]);
			$reimburse = $this->refundPullDown($payment->id);
			$note = ($payment->note ? $payment->note : '<span class="label label-'.$color.'">'.$payment->payment_method.'</span>').' - '.$reimburse;
			$accountLines[] = new AccountLine([
				'note' => /*'P '.*/$note,
				'amount' => $payment->amount,
				'date' => $payment->created_at,
				'ref' => $payment->id,
			]);
		}	
		
		uasort($accountLines, function($a, $b) { return $a->date > $b->date; }); //wow
		
		// build summary column. No other method.
		$tot = 0;
		foreach($accountLines as $l) {
			$l->account = ($tot += $l->amount);
		}
		
		return $accountLines;
	}
	
	public function getBottomLine() {
		$al = $this->getAccountLines();
		if ( ($cnt = count($al)) > 0) {		
			$last = $al[$cnt-1];
			Yii::trace(print_r($last, true), 'Client::getBottomLine');
			return $last->account;
		}
		return 0;
	}
	
	/**
	 *	Client has credit under two forms: 1. Credit notes with money left on them, and 2. Unprocessed reimbursements.
	 *
	 * @return CreditLine[] Credit available.
	 */
	public function getCreditLines() {
		$creditLines = [];
		// Credit notes still open
		foreach(Credit::find()->andWhere(['client_id'=>$this->id])->andWhere(['status' => [Credit::STATUS_TOPAY, Credit::STATUS_SOLDE]])->each() as $document) {
			$creditLines[] = new CreditLine([
				'note' => $document->name,
				'date' => $document->created_at,
				'amount' => - $document->getBalance(),
				'ref' => $document->id,
			]);
		}
		// Unprocessed reimbursements
		/* These are the outstanding "excess" payments */
		foreach($this->getPayments()->andWhere(['status' => Payment::STATUS_OPEN])->each() as $payment) {
			$creditLines[] = new CreditLine([
				'note' => $payment->note,
				'date' => $payment->created_at,
				'amount' => - $payment->amount,
				'ref' => $payment->id,
			]);
		}
		return $creditLines;
	}
}
