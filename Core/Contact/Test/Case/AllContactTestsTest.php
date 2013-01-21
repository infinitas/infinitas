<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllContactTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Contact test');

		$path = CakePlugin::path('Contact') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
