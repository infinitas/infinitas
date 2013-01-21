<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllGoogleTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Google test');

		$path = CakePlugin::path('Google') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
