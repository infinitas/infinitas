<?php
	/* BaseChartEngine Test cases generated on: 2010-12-14 02:12:10 : 1292292070*/
	App::import('helper', 'charts.Charts');

	class BaseChartEnginelibTestCase extends CakeTestCase {
		function startTest() {
			$this->BaseEngine =& new ChartsBaseEngineHelper();
		}

		function endTest() {
			unset($this->BaseEngine);
			ClassRegistry::flush();
		}
	}