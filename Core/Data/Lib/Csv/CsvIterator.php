<?php
App::uses('CsvFileObject', 'Data.Lib/Csv');

/**
 * @brief CsvIterator
 *
 * @param CsvFileObject $_CsvFileObject
 */

class CsvIterator implements Iterator {
/**
 * @brief the max size of a row in the csv file
 *
 * @var integer
 */
	protected $_rowSize = 4096;

/**
 * @brief the current row from the csv file
 *
 * @var array
 */
	protected $_currentRow;

/**
 * @brief count of rows in the csv file
 *
 * @var integer
 */
	protected $_rowCounter;

/**
 * @brief delimiter for fields in the csv file
 *
 * @var string
 */
	protected $_delimiter;

/**
 * @brief the CsvFileObject being used
 *
 * @var CsvFileObject
 */
	protected $_CsvFileObject;

/**
 * @brief set up the options for reading the csv file
 *
 * @param string $file the csv file to read
 * @param string $delimiter the delimiter used for splitting the fields
 * @param integer $rowSize the max size of a single row (0 for unlimited)
 */

	public function __construct(CsvFileObject $CsvFileObject) {
		$this->_CsvFileObject = $CsvFileObject;
	}

/**
 * @brief rewind the Iterator to the begining
 */
	public function rewind() {
		$this->_rowCounter = 0;
		$this->_currentRow = array();
		$this->_CsvFileObject->rewind();

		if($this->_CsvFileObject->hasHeadings()) {
			$this->next();
		}
	}

/**
 * @brief get the current row of the csv file
 *
 * @return array
 */
	public function current() {
		if(empty($this->_currentRow) && $this->valid()) {
			$this->_currentRow = $this->_CsvFileObject->fgetcsv();
		}

		return $this->_currentRow;
	}

/**
 * @brief get the key for the current row
 *
 * @return integer
 */
	public function key() {
		return $this->_rowCounter;
	}

/**
 * @brief go to the next row
 *
 * @return boolean
 */
	public function next() {
		$this->_currentRow = array();
		$this->_CsvFileObject->fgetcsv();
		return $this->_rowCounter++;
	}

/**
 * @brief check if the current row is valid
 *
 * @return boolean
 */
	public function valid(){
		return !$this->_CsvFileObject->eof();
	}
}