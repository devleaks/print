<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class to saved Pdf file.
 */
class Pdf extends _Pdf
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


	public function deleteCascade() {
		unlink($this->filename);
		$this->delete();
	}


	public function sent() {
		$this->sent_at = date('Y-m-d H:i:s');
		return $this->save();
	}

	
	public function send($subject, $body, $email = null) {
		$dest = $email ? $email : $this->client->email;
		if($dest != '') {
			try {
				$mail = Yii::$app->mailer->compose()
					->setFrom( Yii::$app->params['fromEmail'] )
					->setTo( YII_ENV_DEV ? Yii::$app->params['testEmail'] : $dest )
					->setSubject( $subject )
					->setTextBody( $body )
					->attach( $this->filename, ['fileName' => basename($this->filename), 'contentType' => 'application/pdf'] )
					->send();
				$this->sent();
				Yii::$app->session->setFlash('success', Yii::t('store', 'Mail sent').'.');
			} catch (Swift_TransportException $STe) {
				Yii::error($STe->getMessage(), 'Pdf::send::ste');
				Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
			} catch (Exception $e) {
				Yii::error($e->getMessage(), 'Pdf::send::e');				
				Yii::$app->session->setFlash('error', Yii::t('store', 'The system could not send mail.'));
			}
		}
	}

}
