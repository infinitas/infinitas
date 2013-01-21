<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllContentsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Contents test');

		$path = CakePlugin::path('Contents') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
