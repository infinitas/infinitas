<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllUsersTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Users tests');

		$path = CakePlugin::path('Users') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
