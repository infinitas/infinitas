<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllShortUrlsTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All ShortUrls test');

		$path = CakePlugin::path('ShortUrls') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
