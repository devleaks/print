<?php
/**
 * This is the model class for PDF letters.
 */

namespace app\models;

use Yii;
use yii\base\Model;

class PDFDocument extends Model {

	/** Paper Size A4 */
	const FORMAT_A4 = 'A4';
	/** Paper Size A5 */
	const FORMAT_A5 = 'A5';

	/** Location of common views */
	const COMMON_BASE = '@app/modules/store/prints/common/';
	/** Extension of PDF files */
	const PDF_EXT = '.pdf';


	/** Controller used to render views/PDF. */
	protected $controller;
	
}