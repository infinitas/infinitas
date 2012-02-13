<?php
	/* Charts Test cases generated on: 2010-12-14 01:12:17 : 1292291837*/
	App::import('helper', 'charts.Charts');

	class ChartshelperTestCase extends CakeTestCase {
		function startTest() {
			$this->Charts = new ChartsHelper();
		}

		function endTest() {
			unset($this->Charts);
			ClassRegistry::flush();
		}

	}