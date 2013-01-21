<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllInstallerTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Installer test');

		$path = CakePlugin::path('Installer') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
