<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllThemesTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Themes tests');

		$path = CakePlugin::path('Themes') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
