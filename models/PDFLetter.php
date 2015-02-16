<?php
/**
 * This is the model class to generate standard letter with header and footer.
 *
 */

namespace app\models;

use Yii;
use app\components\RuntimeDirectoryManager;

class PDFLetter extends PDFDocument {
	public $language = 'fr';
	public $destination;
	
	/** Example:
			$pdfLetter = new PDFLetter([
				'filename'	=> $filename,
				'content'	=> $content,
				'watermark'	=> $watermark,
			]);
			$result = $pdfLetter->render();
	*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['language'], 'safe'],
        ]);
    }


    /**
     * @inheritdoc
     */
	public function render() {
	    $this->header  = Yii::$app->controller->renderPartial(self::COMMON_BASE.'header', ['language' => $this->language]);
	    $this->footer  = Yii::$app->controller->renderPartial(self::COMMON_BASE.'footer', ['language' => $this->language]);
		return parent::render();
	}

    /**
     * @inheritdoc
     */
	public function generateFilename($name = null) {
		if($this->save)
			$this->filename = RuntimeDirectoryManager::getFilename($this->destination, $name, null, null);
		Yii::trace('filename='.$this->filename, 'PDFLetter::generateFilename');
	}

}
