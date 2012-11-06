<?php
/**
 * @brief EmailSocketException
 */
class EmailSocketException extends SocketException {

}

/**
 * @brief EmailSocketConfigOptionException
 */
class EmailSocketConfigOptionException extends EmailSocketException {
	protected $_messageTemplate = '"%s" is not a valid config option';
}

/**
 * @brief EmailSocketConfigValueException
 */
class EmailSocketConfigValueException extends EmailSocketException {
	protected $_messageTemplate = '"%s" is not a valid value for "%s"';
}

/**
 * @brief EmailSocketCommunicationException
 */
class EmailSocketCommunicationException extends EmailSocketException {
	protected $_messageTemplate = 'Error: %s';
}
