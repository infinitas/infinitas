<?php
class InfinitasPaymentsException extends InfinitasException {

}

/**
 * PaymentConfigTypeException
 *
 * Incorrect payment config type exception
 */
class PaymentConfigTypeException extends InfinitasPaymentsException {

/**
 * The message template for this exception
 *
 * @var string
 */
	protected $_messageTemplate = 'Invalid config type "%s".';
}

class PaymentInvalidRequestException extends InfinitasPaymentsException {

/**
 * The message template for this exception
 *
 * @var string
 */
	protected $_messageTemplate = 'Invalid payment method "%s".';
}

class PaymentEmptyResponse extends InfinitasPaymentsException {

/**
 * The message template for this exception
 *
 * @var string
 */
	protected $_messageTemplate = 'The payment gateway has not responded';
}

class PaymentInvalidResponseException extends InfinitasPaymentsException {

/**
 * The message template for this exception
 *
 * @var string
 */
	protected $_messageTemplate = 'The payment gateway returned with an error';

/**
 * List of errors saved here for displaying in the view
 *
 * @var array
 */
	protected $_attributes = array();

/**
 * Set the errors for the view
 *
 * @param array $errors the errors returned
 * @param integer $code the error code
 *
 * @return void
 */
	public function __construct($errors, $code = null) {
		if (is_array($errors)) {
			$this->_attributes = $errors;
			parent::__construct($this->_messageTemplate, $code);
			return;
		}

		parent::__construct($errors, $code);
	}
}

class PaymentCompletedException extends InfinitasPaymentsException {

/**
 * The message template for this exception
 *
 * @var string
 */
	protected $_messageTemplate = 'This payment has already been completed with status "%s" at %s';
}