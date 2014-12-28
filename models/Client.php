<?php

namespace app\models;

use Yii;
use app\models\Document;
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
	
}
