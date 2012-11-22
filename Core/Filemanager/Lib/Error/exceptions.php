<?php
/**
 * Filemanager exceptions
 *
 * @package Infinitas.Filemanager.Error
 */

/**
 * FilemanagerException
 *
 * @package Infinitas.Filemanager.Error
 */
class FilemanagerException extends InfinitasException {

}

/**
 * NotValidLinkSourceException
 *
 * @package Infinitas.Filemanager.Error
 */
class NotValidLinkSourceException extends FilemanagerException {
	protected $_messageTemplate = 'The path "%s" can not be used for symlinking';

}

/**
 * PathIsDirectoryException
 *
 * @package Infinitas.Filemanager.Error
 */
class PathIsDirectoryException extends FilemanagerException {
	protected $_messageTemplate = 'The path "%s" already exists as a path';

}

/**
 * PathIsFileException
 *
 * @package Infinitas.Filemanager.Error
 */
class PathIsFileException extends FilemanagerException {
	protected $_messageTemplate = 'The path "%s" already exists as a file';

}

