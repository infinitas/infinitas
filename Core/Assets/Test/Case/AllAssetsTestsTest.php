<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllAssetsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Assets test');

		$path = CakePlugin::path('Assets') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
