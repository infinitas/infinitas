<?php
class CsvFromArray {

/**
 * The csv files headings
 * 
 * @var array
 */
	protected $_headings = array();

/**
 * The csv files rows
 * 
 * @var array
 */
	protected $_rows = array();

/**
 * The configuration in use by the instance
 * 
 * @var array
 */
	protected $_config = array();

/**
 * The default configuration options
 * 
 * @var array
 */
	protected $_default = array(
		'delimiter' => ';',
		'enclosure' => '"',
		'encloseAll' => false,
		'mysqlNull' => false,
		'headings' => true
	);

/**
 * The count of coloumns
 *
 * This is used to check that a valid number of columns are added as the file is being 
 * added too. It is calculated by the first row or heading added to the file.
 * 
 * @var integer|null
 */
	protected $_columnCount = null;

/**
 * Instance of the File class
 * 
 * @var File
 */
	protected $_File;

/**
 * Initialise the file and configs
 * 
 * @param string $path the path to save the csv file to
 * @param array $config the configuration options
 * 
 * @return void
 */
	public function __construct($path, array $config = array()) {
		$this->_file($path);
		$this->config($config);
	}

/**
 * Change the csv configuration
 * 
 * @param array $config the config options to use
 * 
 * @return void
 */
	public function config(array $config) {
		$this->_config = array_merge($this->_default, $config);
	}

/**
 * Set the headings for the csv file
 *
 * Headings can be set at any point so long as once rows have been added the headings match 
 * the number of columns.
 * 
 * @param  array $fields the values for the csv files headings
 * 
 * @return void
 *
 * @throws InvalidArgumentException
 */
	public function headings(array $fields) {
		if (!empty($this->_headings) && !empty($this->_rows) && count($this->_headings) !== count($fields)) {
			throw new InvalidArgumentException(__d('data', 'Invalid number of columns, expected %d got %d', count($this->_headings), count($fields)));
		}
		if ($this->_columnCount === null) {
			$this->_columnCount = count($fields);
		}

		$this->_headings = $fields;
	}

/**
 * Wrapper to add multiple rows at once
 *
 * $rows should contain a numerical indexed array. If a string keyed array is 
 * passed it is assumed to be a single row and will be wrapped in an array to 
 * create the expected numerical array
 * 
 * @param array $rows the rows of data to add to the file
 * 
 * @return void
 */
	public function rows(array &$rows) {
		if (!Hash::numeric(array_keys($rows))) {
			$rows = array($rows);
		}

		foreach ($rows as $row) {
			$this->row($row);
		}
	}

/**
 * Add a row to the csv file
 *
 * All rows should have the same number of columns, if not an exception will be thrown.
 * 
 * @param array $fields the fields for the row being added
 * 
 * @return void
 */
	public function row(array $fields) {
		$fields = Hash::flatten($fields);
		if ($this->_config('headings') && empty($this->_headings)) {
			$this->headings(array_keys($fields));
		}

		$fields = array_values($fields);
		if ($this->_columnCount === null) {
			$this->_columnCount = count($fields);
		}

		if ($this->_columnCount != count($fields)) {
			throw new InvalidArgumentException(__d('data', 'Invalid number of columns, expected %d got %d', $this->_columnCount, count($row)));
		}

		$this->_rows[] = $fields;
	}

/**
 * Resets the rows and headings
 *
 * Headings is optional, defaults to false. This is used for the append so 
 * that data can be cleared after each write and not duplicated in the file.
 * 
 * @param boolean $headings pass true to clear headings also.
 * 
 * @return array
 */
	public function reset($headings = false) {
		$this->_rows = array();
		if ($headings) {
			$this->_headings = array();
		}

		return true;
	}

	public function write(array $rows = array()) {
		if ($rows) {
			$this->rows($rows);
		}
		if ($this->_file()->write($this->_escaped())) {
			return $this->reset();
		}

		return false;
	}

	public function append(array $rows = array()) {
		if ($rows) {
			$this->rows($rows);
		}

		clearstatcache();
		if (!$this->_file()->size()) {
			return $this->write();
		}

		if ($this->_file()->append($this->_escaped(true))) {
			return $this->reset();
		}

		return false;
	}

/**
 * Fetch a config value
 * 
 * @param  [type] $field [description]
 * @return array
 */
	protected function _config($field) {
		if (empty($this->_config)) {
			$this->config(array());
		}

		if (array_key_exists($field, $this->_config)) {
			return $this->_config[$field];
		}

		return null;
	}

/**
 * Get an instace of the File
 *
 * If path is specified a new File class object is created
 * 
 * @param string $path the path of the file to be saved
 * 
 * @return File
 */
	protected function _file($path = null) {
		if ($path !== null) {
			$this->_File = new File($path, true);
		}

		if (!($this->_File instanceof File)) {
			throw new InvalidArgumentException(__d('data', 'No file specified for saving'));
		}

		return $this->_File;
	}

/**
 * Escape all the data in the array and build the csv data
 *
 * If the $append param is true headings will no be added to the file. This is so that the file
 * can be incrementally updated without adding the headings again.
 *
 * @param boolean $append appending to an existing file
 * 
 * @return string
 */
	protected function _escaped($append = false) {
		$delimiter = $this->_config('delimiter');
		$enclosure = $this->_config('enclosure');
		$delimiter_esc = preg_quote($delimiter, '/');
		$enclosure_esc = preg_quote($enclosure, '/');
		$mysqlNull = $this->_config('mysqlNull');
		$encloseAll = $this->_config('encloseAll');

		$rows = $this->_rows;
		if ($append === false && $this->_headings) {
			array_unshift($rows, $this->_headings);	
		}
		
		$output = array();
		foreach ($rows as $fields) {
			foreach ($fields as &$field) {
				if ($field === null && $mysqlNull) {
					$field = 'NULL';
					continue;
				}

				if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
					$field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
				}
			}
			$output[] = implode($delimiter, $fields);
		}

		return trim(implode("\n", $output), "\n") . "\n";
	}
}