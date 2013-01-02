<?php
class CronsException extends InfinitasException {

	public function __construct($message, $code = 500) {
		parent::__construct($message, $code);
	}
}

class CronsNotStartedException extends CronsException {
	protected $_messageTemplate = 'Cron not yet started';
}
