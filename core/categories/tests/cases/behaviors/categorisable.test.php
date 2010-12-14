<?php
	/* Categorisable Test cases generated on: 2010-12-14 01:12:28 : 1292289928*/
	App::import('behavior', 'Categories.Categorisable');

	class CategorisablebehaviorTestCase extends CakeTestCase {
		function startTest() {
			$this->Categorisable = new Categorisablebehavior();
		}

		function endTest() {
			unset($this->Categorisable);
			ClassRegistry::flush();
		}
	}