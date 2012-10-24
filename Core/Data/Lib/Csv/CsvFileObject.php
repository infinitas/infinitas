<?php
/**
 * @brief CsvFileObject for reading csv data from files
 *
 * This creates a SplFileObject class suited for reading / parsing csv files. It
 * allows passing all the options required and configures the object with
 * SplFileObject::setCsvControl().
 *
 * See CsvFileObject::__construct() for the defaults and options that can be passed
 *
 * @see http://php.net/manual/en/class.splfileobject.php
 * @see http://www.php.net/manual/en/splfileobject.setcsvcontrol.php
 */

class CsvFileObject extends SplFileObject {
/**
 * @brief does the csv file contain a heading as the first row
 *
 * @var boolean
 */
	protected $_hasHeading;

/**
 * @brief internal cache of the headings
 *
 * @var array
 */
	protected $_headings = array();
/**
 * @brief set up the csv iterator object
 *
 * $settings can contain the following:
 *	- mode: @see http://www.php.net/manual/en/function.fopen.php
 *	- include_path: boolean Whether to search in the include_path for filename.
 *  - delimiter: the csv field delimiter (default ,)
 *  - enclosure: the csv field enclosure (default ")
 *	- escape: the csv data escape char (default \)
 *	- heading: boolean, true if first row is headings, false if not (default true)
 *
 * @param string $file the file to be loaded
 * @param array $settings
 *
 * @return void
 */
	public function __construct($file, $settings = array()) {
		$settings = array_merge(array(
			'mode' => 'r',
			'include_path' => false,
			'resource' => null,
			'delimiter' => ',',
			'enclosure' => '"',
			'escape' => '\\',
			'heading' => true
		), $settings);

		$this->_hasHeading = $settings['heading'];

		parent::__construct($file, $settings['mode'], $settings['include_path']);

		$this->setCsvControl($settings['delimiter'], $settings['enclosure'], $settings['escape']);

		$this->heading();
	}

/**
 * @brief check if the file has headings
 *
 * @return boolean
 */
	public function hasHeadings() {
		return $this->_hasHeading;
	}

/**
 * @brief get headings from the csv file
 *
 * If there are headings available (see the settings) this will get them and return
 * an array that has been fomatted to suite easy importing.
 *
 * @return array
 */
	public function heading() {
		if($this->hasHeadings() && empty($this->_headings)) {
			$this->rewind();
			$this->_headings = $this->fgetcsv('_heading_');

			foreach($this->_headings as &$heading) {
				$heading = Inflector::slug(strtolower($heading));
			}
		}

		return $this->_headings;
	}

/**
 * @brief overload the method to create array with heading => value
 *
 * @todo rename this method and not call it from the iterator
 *
 * @param string $a used to identify if fetching a header row to skip doing the manipulation
 * @param string $b not used, only for compatibility
 * @param string $c not used, only for compatibility
 *
 * @return array
 */
	public function fgetcsv($a = null, $b = null, $c = null) {
		$headings = array();
		if($a != '_heading_') {
			$headings = $this->heading();
		}

		$row = parent::fgetcsv();

		if(count($row) === 1 && is_null(current($row))) {
			return array();
		}

		if(!empty($headings)) {
			$row = array_combine($headings, $row);
		}

		return $row;
	}
}