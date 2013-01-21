<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllFilemanagerTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Filemanager test');

		$path = CakePlugin::path('Filemanager') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
