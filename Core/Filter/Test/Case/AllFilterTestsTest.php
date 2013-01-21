<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllFilterTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Filter test');

		$path = CakePlugin::path('Filter') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
