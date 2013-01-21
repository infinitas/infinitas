<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllSecurityTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Security tests');

		$path = CakePlugin::path('Security') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
