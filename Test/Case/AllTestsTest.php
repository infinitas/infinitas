<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new static('All Application Tests');
		$path = APP . 'Test' . DS . 'Case' . DS;
		$suite->addTestFile($path . 'AllCoreTestsTest.php');
		$suite->addTestFile($path . 'AllDeveloperTestsTest.php');
		$suite->addTestFile($path . 'AllPluginTestsTest.php');
		return $suite;
	}
}
