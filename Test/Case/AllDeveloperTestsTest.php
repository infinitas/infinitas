<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllDeveloperTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Developer plugins test');
		$plugins = App::objects('plugin', APP . 'Developer', false);

		foreach ($plugins as $plugin) {
			$path = CakePlugin::path($plugin) . 'Test' . DS . 'Case' . DS;
			$suite->addTestDirectoryRecursive($path);
		}
		return $suite;
	}
}
