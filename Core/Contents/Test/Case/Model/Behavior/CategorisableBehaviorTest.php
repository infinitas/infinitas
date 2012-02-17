<?php
	/* Categorisable Test cases generated on: 2010-12-14 01:12:28 : 1292289928*/
	App::uses('CategorisableBehavior', 'Contents.Model/Behavior');

	class CategorisableBehaviorTest extends CakeTestCase {
		function startTest() {
			$this->Categorisable = new CategorisableBehavior();
		}

		function testDummy() {}

		function endTest() {
			unset($this->Categorisable);
			ClassRegistry::flush();
		}
	}