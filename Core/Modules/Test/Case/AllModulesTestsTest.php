<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllModulesTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Modules test');

		$path = CakePlugin::path('Modules') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
