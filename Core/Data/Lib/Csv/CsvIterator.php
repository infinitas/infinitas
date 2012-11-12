<?php
App::uses('CsvFileObject', 'Data.Lib/Csv');

/**
 * CsvIterator
 *
 * @param CsvFileObject $_CsvFileObject
 */

class CsvIterator implements Iterator {
/**
 * the current row from the csv file
 *
 * @var array
 */
	protected $_currentRow;

/**
 * count of rows in the csv file
 *
 * @var integer
 */
	protected $_rowCounter;

/**
 * the CsvFileObject being used
 *
 * @var CsvFileObject
 */
	protected $_CsvFileObject;

/**
 * set up the options for reading the csv file
 *
 * @param string $file the csv file to read
 * @param integer $rowSize the max size of a single row (0 for unlimited)
 */

	public function __construct(CsvFileObject $CsvFileObject) {
		$this->_CsvFileObject = $CsvFileObject;
	}

/**
 * rewind the Iterator to the begining
 */
	public function rewind() {
		$this->_rowCounter = 0;
		$this->_currentRow = array();
		$this->_CsvFileObject->rewind();

		if($this->_CsvFileObject->hasHeadings()) {
			$this->_CsvFileObject->read();
		}
	}

/**
 * get the current row of the csv file
 *
 * @return array
 */
	public function current() {
		if(empty($this->_currentRow) && $this->valid()) {
			$this->_currentRow = $this->_CsvFileObject->read();
		}

		return $this->_currentRow;
	}

/**
 * get the key for the current row
 *
 * If the csv file has headings the counter is returned as $normalCount - 1
 *
 * @return integer
 */
	public function key() {
		return $this->_rowCounter;
	}

/**
 * go to the next row
 *
 * @return boolean
 */
	public function next() {
		$this->_currentRow = array();
		$this->current();

		if($this->valid()) {
			return $this->_rowCounter++;
		}

		return $this->_rowCounter;
	}

/**
 * check if the current row is valid
 *
 * @return boolean
 */
	public function valid(){
		return !$this->_CsvFileObject->eof();
	}

}