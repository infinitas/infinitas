<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllWebmasterTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Webmaster test');

		$path = CakePlugin::path('Webmaster') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
