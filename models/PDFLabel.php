<?php
/**
 * This is the model class to generate standard labels items or orders
 * Format is A5, always. Output is PDF document, always.
 *
 */

namespace app\models;

use Yii;

class PDFLabel extends PDFDocument {

	/** Example:
			$pdfLabel = new PDFLabel([
				'content'	=> $content,
			]);
			$result = $pdfLabel->render();
	*/

    /**
     * @inheritdoc
     */
	public function render() {
		$this->format = PDFDocument::FORMAT_A5;
	    $this->header  = '';
	    $this->footer  = '';
		return parent::render();
	}

}