<?php
/**
 * Api Config Model
 *
 * For interacting with the Config files for ApiGenerator
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.models
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiConfig extends Object {
/**
 * holds data for read
 *
 * @var array
 **/
	public $data = array();
/**
 * holds path to current config file
 *
 * @var string
 **/
	public $path = null;
/**
 * Constructor
 *
 * @return void
 *
 **/
	public function __construct() {
		$this->path =  dirname(dirname(__FILE__)) . DS . 'config' . DS . 'api_config.ini';
	}
/**
 * Read from config file or passed array
 *
 * @param mixed $lines absolute file path, array, or string
 * @return array
 *
 **/
	public function read($lines = array()) {
		if (empty($lines)) {
			if (!empty($this->data)) {
				return $this->data;
			}
			$lines = $this->path;
		}

		if (is_string($lines)) {
			$isPath = $lines[0] === '/' || $lines[1] === ':';
			if ($isPath && file_exists($lines)) {
				$lines = file_get_contents($lines);
			} elseif ($isPath && !file_exists($lines)) {
				return array();
			}

			$lines = str_replace(array("\r\n", "\r"), "\n", $lines);
			$lines = explode("\n", $lines);
		}
		if (empty($lines)) {
			return array();
		}
		$ini = array();

		$lines = array_filter($lines);
		foreach ($lines as $line) {
			$row = trim($line);
			if (empty($row) || $row[0] == ';') {
				continue;
			}

			if ($row[0] == '[' && substr($row, -1, 1) == ']') {
				$section = preg_replace('/[\[\]]/', '', $row);
			} else {
				$delimiter = strpos($row, '=');
				if ($delimiter > 0) {
					$key = trim(substr($row, 0, $delimiter));
					$value = trim(substr($row, $delimiter + 1));

					if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
						$value = substr($value, 1, -1);
					}
					$ini[$section][$key] = stripcslashes($value);
				} else {
					if (!isset($section)) {
						$section = '';
					}
					$ini[$section][trim($row)] = '';
				}
			}
		}
		if (isset($ini['paths'])) {
			$paths = array();
			foreach ($ini['paths'] as $path => $value) {
				$paths[$this->makeAbsolute($path)] = $value;
			}
			$ini['paths'] = $paths;
		}
		if (isset($ini['mappings'])) {
			$mappings = array();
			foreach ($ini['mappings'] as $class => $mapping) {
				$mappings[$class] = $this->makeAbsolute(dirname($mapping)) . DS . basename($mapping);
			}
			$ini['mappings'] = $mappings;
		}
		return $this->data = $ini;
	}
/**
 * Save a config
 *
 * @param mixed $path
 * @param mixed $string
 * @return boolean
 **/
	public function save($path = null, $string = null) {
		if (empty($path) && empty($string)) {
			return false;
		}

		if (!empty($path) && empty($string)) {
			$string = $path;
			$path = $this->path;
		}

		if (is_array($string)) {
			$string = $this->toString($string);
		}

		$File = new File($path, true, 0755);
		if ($File->write($string)) {
			return true;
		}
		return false;
	}
/**
 * Get the path at index
 *
 * @param int $index Index of file path to get. defaults to 0
 * @return string Absolute file path read from config.
 **/
	public function getPath($index = 0) {
		if (empty($this->data)) {
			$this->read();
		}
		if (empty($this->data['paths'])) {
			trigger_error(sprintf('Paths missing from %s',  'APP' . DS . 'config' . DS . 'api_config.ini'), E_USER_ERROR);
			return false;
		}
		$paths = array_keys($this->data['paths']);
		if (!isset($paths[$index])) {
			return false;
		}
		return $paths[$index];
	}
/**
 * Return data as a config string
 *
 * @return void
 **/
	public function toString($data = array()) {
		if (empty($data)) {
			if(empty($this->data)) {
				return false;
			}
			$data = $this->data;
		}
		$result = array();
		foreach ($data as $key => $value) {
			if ($key[0] != '[') {
				$result[] = "[$key]";
			}
			if (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					$result[] = "{$key2} = " . trim(var_export($value2, true), "'");
				}
			}
		}
		if (empty($result)) {
			$result = $data;
		}
		return join("\n", $result);
	}
/**
 * If path provided is not absolute, prepends CORE_PATH, evaluates .. and
 * corrects directory separators for the current OS
 *
 * @param string $path
 * @return string
 */
	public function makeAbsolute($path) {
		if (Folder::isAbsolute($path)) {
			return $path;
		}
		$path = CORE_PATH . $path;
		$Folder = new Folder($path);
		return $Folder->path;
	}
}
?>