<?php
	/* Charts Test cases generated on: 2010-12-14 01:12:17 : 1292291837*/
	App::uses('ChartsHelper', 'Charts.View/Helper');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class ChartsHelperTest extends CakeTestCase {
		function startTest() {
			$this->Charts = new ChartsHelper(new View(new Controller()));
		}

		function testDummy() {}

		function endTest() {
			unset($this->Charts);
			ClassRegistry::flush();
		}

	}