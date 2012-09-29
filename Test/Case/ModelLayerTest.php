<?php
class ModelLayerTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		App::uses('Model', 'Model');
		$suite = new CakeTestSuite('Model Layer Tests');

		$it = new ModelLayerTestsFilterIterator(
                  new RecursiveIteratorIterator(
                      new RecursiveDirectoryIterator(APP)));

		for ($it->rewind(); $it->valid(); $it->next()) {
			$suite->addTestFile($it->current()->getPathname());
		}

		return $suite;
	}
}

/**
 * @brief Iterator for finding event test files
 */
class ModelLayerTestsFilterIterator extends FilterIterator {
/**
 * @brief find all event test files
 *
 * Will return true if its a match
 *
 * @return boolean
 */
	public function accept() {
		if($this->current()->getExtension() == 'php') {
			return strstr($this->current(), 'Test/Case/Model');
		}
	}
}
