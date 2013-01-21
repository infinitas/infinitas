<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllMenusTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Menus test');

		$path = CakePlugin::path('Menus') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
