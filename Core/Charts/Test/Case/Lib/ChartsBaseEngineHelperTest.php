<?php
	/* BaseChartEngine Test cases generated on: 2010-12-14 02:12:10 : 1292292070*/
	App::uses('ChartsBaseEngineHelper', 'Charts.Lib');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class ChartsBaseEngineHelperTest extends CakeTestCase {
		function startTest() {
			$this->BaseEngine = new ChartsBaseEngineHelper(new View(new Controller()));
		}

		function testDummy() {}

		function endTest() {
			unset($this->BaseEngine);
			ClassRegistry::flush();
		}
	}