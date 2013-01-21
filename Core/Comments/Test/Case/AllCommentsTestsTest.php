<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllCommentsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Comments test');

		$path = CakePlugin::path('Comments') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
