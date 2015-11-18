<?php

namespace app\components;

use yii\validators\Validator;
use Yii;

class VATValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
		if (isset($model->$attribute)) {
			if (strpos(strtolower($model->$attribute), 'non') !== false)
				return;
			if ( $model->vat_check ) {
				$euVATValidator = new EuVATValidator();
				if (! $euVATValidator->verifyVatNumber($model->$attribute)) {
					$this->addError($model, $attribute, $euVATValidator->cleanVatNumber($model->$attribute).' is not a valid VAT number.');
				}
			}
		}
	}
}
