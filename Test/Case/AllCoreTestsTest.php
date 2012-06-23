<?php
class AllCoreTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Core plugins test');
		$path = APP . 'Test' . DS . 'Case' . DS;
		$suite->addTestFile($path . 'AppControllerTest.php');
		$suite->addTestFile($path . 'AppModelTest.php');
		$suite->addTestFile($path . 'AppHelperHelperTest.php');

		$plugins = App::objects('plugin', APP . 'Core', false);
		foreach ($plugins as $plugin) {
			if (CakePlugin::loaded($plugin)) {
				$file = CakePlugin::path($plugin) . 'Test' . DS . 'Case' . DS . 'All' . $plugin . 'TestsTest.php';
				if (file_exists($file)) {
					$suite->addTestFile($file);
				}
			}
		}
		return $suite;
	}
}
