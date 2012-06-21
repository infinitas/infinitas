<?php
class AllTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All plugins test');
		$suite->addTestFile('AllCoreTestsTest');
		$suite->addTestFile('AllDeveloperTestsTest');
		$suite->addTestFile('AllPluginTestsTest');
		return $suite;
	}
}
