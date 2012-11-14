<?php
/**
 * Security Exceptions
 *
 * @package Infinitas.Security.Error
 */

/**
 * Base Security plugin exception
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Security.Error
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class SecurityException extends InfinitasException {
/**
 * Custom layout
 *
 * @var string
 */
	public $layout = 'error_security';

/**
 * Get the set layout
 *
 * @return string
 */
	public function getCustomLayout() {
		return $this->layout();
	}

}


/**
 * Blocked Ip address exception
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Security.Error
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class SecurityIpAddressBlockedException extends SecurityException {
/**
 * Message template
 *
 * @var string
 */
	protected $_messageTemplate = 'Your IP address "%s" has been temporarily blocked.';

/**
 * Constructor
 *
 * Used to set the default error code to 503 for blocked ip addresses
 *
 * @param string|array $message the message
 * @param integer $code the error code
 *
 * @return void
 */
	public function __construct($message, $code = 503) {
		parent::__construct($message, $code);
	}

}

/**
 * Blocked exception
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Security.Error
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasSecurityBlockedException extends Exception {

}