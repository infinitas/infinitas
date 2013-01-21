<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllRoutesTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Routes tests');

		$path = CakePlugin::path('Routes') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
