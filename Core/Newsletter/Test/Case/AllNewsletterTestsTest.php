<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllNewsletterTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Newsletter test');

		$path = CakePlugin::path('Newsletter') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
