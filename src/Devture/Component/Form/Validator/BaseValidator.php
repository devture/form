<?php
namespace Devture\Component\Form\Validator;

abstract class BaseValidator implements ValidatorInterface {

	public function validate($object, array $options = array()): ViolationsList {
		return new ViolationsList();
	}

	protected function isEmpty($value): bool {
		return in_array($value, array('', null), true);
	}

}
