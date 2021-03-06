<?php
/**
 * This is the model class to generate standard letter with header and footer.
 *
 */

namespace app\models;

use Yii;
use app\components\RuntimeDirectoryManager;

class PDFAccount extends PDFLetter {
	public $client;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['client'], 'safe'],
        ]);
    }


    /**
     * @inheritdoc
     */
	public function generateFilename($name = null) {
		if($this->save)
			$this->filename = RuntimeDirectoryManager::getFilename($this->destination, $name, null, $this->client);
		Yii::trace('filename='.$this->filename, 'PDFLetter::generateFilename');
	}

    /**
     * @inheritdoc
     */
	public function save() {
		//Yii::trace('fn='.$this->filename, 'PDFAccount::save');
		if($this->filename) {
			$this->deletePrevious();
			$pdf = new Pdf([
				'document_type' => $this->destination,
				'filename' => $this->filename,
				'client_id' => $this->client->id,
			]);
			return $pdf->save();
		}
		return false;
	}

}
