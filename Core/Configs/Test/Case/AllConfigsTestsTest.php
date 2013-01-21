<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllConfigsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Configs test');

		$path = CakePlugin::path('Configs') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
