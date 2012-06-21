<?php
class AllTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All test');
		$path = APP . 'Test' . DS . 'Case' . DS;
		$suite->addTestFile($path . 'AllCoreTestsTest.php');
		$suite->addTestFile($path . 'AllDeveloperTestsTest.php');
		$suite->addTestFile($path . 'AllPluginTestsTest.php');
		return $suite;
	}
}
