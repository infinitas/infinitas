<?php
class AllTestsBase extends PHPUnit_Framework_TestSuite {

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
				$coverageFilter->addDirectoryToBlacklist(APP . DS . 'CakePHP');
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
