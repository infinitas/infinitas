<?php
class ViewLayerTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('View Layer Tests');

		$it = new ViewLayerTestsFilterIterator(
                  new RecursiveIteratorIterator(
                      new RecursiveDirectoryIterator(APP)));

		for ($it->rewind(); $it->valid(); $it->next()) {
			$suite->addTestFile($it->current()->getPathname());
		}

		return $suite;
	}
}

/**
 * @brief Iterator for finding view related test files
 */
class ViewLayerTestsFilterIterator extends FilterIterator {
/**
 * @brief find all view test files
 *
 * Will return true if its a match
 *
 * @return boolean
 */
	public function accept() {
		if($this->current()->getExtension() == 'php') {
			return strstr($this->current(), 'Test/Case/View');
		}
	}
}
