<?php
	/* AppHelper Test cases generated on: 2010-12-14 01:12:39 : 1292290119*/
	App::import('Helper', 'AppHelper');

	class AppHelperHelperTest extends CakeTestCase {
		function startTest() {
		}

		function endTest() {
			unset($this->AppHelper);
			ClassRegistry::flush();
		}

		public function testSomething() {
			$this->assertTrue(true);
		}
	}