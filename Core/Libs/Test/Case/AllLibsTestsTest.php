<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllLibsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Libs test');

		$path = CakePlugin::path('Libs') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
