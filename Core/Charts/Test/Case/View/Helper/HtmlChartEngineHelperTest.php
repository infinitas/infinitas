<?php
	/* HtmlChartEngine Test cases generated on: 2010-12-14 01:12:23 : 1292291603*/
	App::uses('HtmlChartEngineHelper', 'Charts.View/Helper');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class HtmlChartEngineHelperTest extends CakeTestCase {
		function startTest() {
			$this->HtmlChartEngine = new HtmlChartEngineHelper(new View(new Controller()));
		}

		function testDummy() {}

		function endTest() {
			unset($this->HtmlChartEngine);
			ClassRegistry::flush();
		}
	}