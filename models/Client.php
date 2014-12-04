<?php

namespace app\models;

use Yii;
use app\models\Document;
use kartik\helpers\Html as KHtml;
use yii\db\ActiveRecord;
use yii\helpers\Html;

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
     * create client label for on screen display
	 */
    public function makeAddress($upd_link = false, $ret = Document::TYPE_ORDER)
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
		        'heading' => $this->prenom.' '.$this->nom.($upd_link ?
						Html::a('<i class="glyphicon glyphicon-pencil pull-right"></i>', ['../store/client/maj', 'id' => $this->id, 'ret' => $ret])
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
