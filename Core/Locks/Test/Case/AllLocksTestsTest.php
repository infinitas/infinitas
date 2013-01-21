<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllLocksTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Locks test');

		$path = CakePlugin::path('Locks') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
