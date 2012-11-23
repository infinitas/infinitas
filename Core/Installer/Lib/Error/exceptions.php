<?php
/**
 * Installer exceptions
 *
 * @package Infinitas.Installer.Error
 */

/**
 * InstallerException
 *
 * @package Infinitas.Installer.Error
 */
class InstallerException extends InfinitasException {

}

/**
 * InstallerConfigurationException
 *
 * @package Infinitas.Installer.Error
 */
class InstallerConfigurationException extends InstallerException {
	protected $_messageTemplage = 'Configuration for "%s" is %s';

}