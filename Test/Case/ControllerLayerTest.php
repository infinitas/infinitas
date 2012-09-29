<?php
class ControllerLayerTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		//App::uses('Model', 'Model');
		$suite = new CakeTestSuite('Controller Layer Tests');

		$it = new ControllerLayerTestsFilterIterator(
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
class ControllerLayerTestsFilterIterator extends FilterIterator {
/**
 * @brief find all event test files
 *
 * Will return true if its a match
 *
 * @return boolean
 */
	public function accept() {
		if($this->current()->getExtension() == 'php') {
			return strstr($this->current(), 'Test/Case/Controller');
		}
	}
}
