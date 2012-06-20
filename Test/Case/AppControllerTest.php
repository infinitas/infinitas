<?php
	/* AppHelper Test cases generated on: 2010-12-14 01:12:39 : 1292290119*/
	App::import('Controller', 'AppController');

	class TestAppControllerController extends AppController {
		public $autoRender = false;

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class AppControllerTest extends CakeTestCase {
		public function startTest() {
			$this->AppController = new AppController();
			$this->AppController->constructClasses();
		}

		public function endTest() {
			unset($this->AppController);
			ClassRegistry::flush();
		}

		public function testSomething() {
			$this->assertTrue(true);
		}
	}