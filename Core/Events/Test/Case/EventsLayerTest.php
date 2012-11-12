<?php
class EventsLayerTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('Events Tests');

		$it = new AllEventsTestsFilterIterator(
                  new RecursiveIteratorIterator(
                      new RecursiveDirectoryIterator(APP)));

		for ($it->rewind(); $it->valid(); $it->next()) {
			$suite->addTestFile($it->current()->getPathname());
		}

		return $suite;
	}
}

/**
 * Iterator for finding event test files
 */
class AllEventsTestsFilterIterator extends FilterIterator {
/**
 * find all event test files
 *
 * Will return true if its a match
 *
 * @return boolean
 */
	public function accept() {
		if($this->current()->getExtension() == 'php') {
			return strstr($this->current(), 'EventsTest.php');
		}
	}
}
