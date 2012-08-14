<?php
class AllTestsTest extends PHPUnit_Framework_TestSuite {

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

	public function run(PHPUnit_Framework_TestResult $result = null, $filter = false, array $groups = array(), array $excludeGroups = array(), $processIsolation = false) {
		if ($result === null) {
			$result = $this->createResult();
		}

		if (!isset($this->coverageSetup) || !$this->coverageSetup) {
			$coverage = $result->getCodeCoverage();
			if(!empty($coverage)) {
				$coverage->setProcessUncoveredFilesFromWhitelist(true);

				$coverageFilter = $coverage->filter();

				$coverageFilter->addDirectoryToBlacklist(CORE_PATH);
				$coverageFilter->addDirectoryToBlacklist(APP . DS . 'Test');
				$coverageFilter->addDirectoryToBlacklist(APP . DS . 'Config');
				foreach(InfinitasPlugin::listPlugins('all') as $plugin) {
					$coverageFilter->addDirectoryToBlacklist(CakePlugin::path($plugin) . 'Test');
				}

				$this->coverageSetup = true;
			}
		}
		return parent::run($result, $filter, $groups, $excludeGroups, $processIsolation);
	}
}
