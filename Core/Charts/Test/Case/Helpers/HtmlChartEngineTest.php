<?php
	/* HtmlChartEngine Test cases generated on: 2010-12-14 01:12:23 : 1292291603*/
	App::import('helper', 'charts.ChartsHelper');

	class HtmlChartEnginehelperTestCase extends CakeTestCase {
		function startTest() {
			$this->HtmlChartEngine = new HtmlChartEngineHelper();
		}

		function endTest() {
			unset($this->HtmlChartEngine);
			ClassRegistry::flush();
		}
	}