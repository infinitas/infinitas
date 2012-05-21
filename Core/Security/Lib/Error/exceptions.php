<?php
	/**
	 * @brief base Security plugin exception 
	 */
	class SecurityException extends InfinitasException {
		public $layout = 'error_security';
		
		public function getCustomLayout() {
			return $this->layout();
		}
	}
	
	/**
	 * @brief exception thrown when a users IP address has been blocked 
	 */
	class SecurityIpAddressBlockedException extends SecurityException {
		protected $_messageTemplate = 'Your IP address "%s" has been temporarily blocked.';

		public function __construct($message, $code = 503) {
			parent::__construct($message, $code);
		}
	}