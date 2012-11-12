<?php
/**
 * EmailSocketException
 */
class EmailSocketException extends SocketException {

}

/**
 * EmailSocketConfigOptionException
 */
class EmailSocketConfigOptionException extends EmailSocketException {
	protected $_messageTemplate = '"%s" is not a valid config option';
}

/**
 * EmailSocketConfigValueException
 */
class EmailSocketConfigValueException extends EmailSocketException {
	protected $_messageTemplate = '"%s" is not a valid value for "%s"';
}

/**
 * EmailSocketCommunicationException
 */
class EmailSocketCommunicationException extends EmailSocketException {
	protected $_messageTemplate = 'Error: %s';
}
