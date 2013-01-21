<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllViewCounterTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All ViewCounter test');

		$path = CakePlugin::path('ViewCounter') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
