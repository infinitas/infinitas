<?php
class AllAssetsTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Assets test');

		$path = CakePlugin::path('Assets') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectory($path . 'Lib');
		$suite->addTestDirectory($path . 'View' . DS . 'Helper');

		return $suite;
	}
}
