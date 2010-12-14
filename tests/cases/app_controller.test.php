<?php
	/* AppHelper Test cases generated on: 2010-12-14 01:12:39 : 1292290119*/
	App::import('Controller', 'AppController');

	class TestAppControllerController extends AppController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class AppControllerTestCase extends CakeTestCase {
		function startTest() {
			$this->AppController = new AppController();
			$this->AppController->constructClasses();
		}

		function endTest() {
			unset($this->AppController);
			ClassRegistry::flush();
		}
	}