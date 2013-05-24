<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllInfinitasPaymentsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All InfinitasPayments test');

		$path = CakePlugin::path('InfinitasPayments') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
