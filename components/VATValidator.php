<?php

namespace app\components;

use yii\validators\Validator;

class VATValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
		if (isset($model->$attribute)) {
			if(strpos(strtolower($model->$attribute), 'non') !== false)
				return;
			$euVATValidator = new EuVATValidator();
			if (! $euVATValidator->verifyVatNumber($model->$attribute)) {
				$this->addError($model, $attribute, $euVATValidator->cleanVatNumber($model->$attribute).' is not a valid VAT number.');
			}
		}
	}
}
