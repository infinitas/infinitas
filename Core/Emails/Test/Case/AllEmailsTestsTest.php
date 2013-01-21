<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllEmailsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Emails test');

		$path = CakePlugin::path('Emails') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
