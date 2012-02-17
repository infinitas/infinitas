<?php
	/* Configs Test cases generated on: 2010-12-14 12:12:49 : 1292330689*/
	App::uses('ConfigsController', 'Configs.Controller');

	class TestConfigsController extends ConfigsController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class ConfigsControllerTest extends CakeTestCase {
		function startTest() {
			$this->Configs = new TestConfigsController();
			$this->Configs->constructClasses();
		}

		function testDummy() {}

		function endTest() {
			unset($this->Configs);
			ClassRegistry::flush();
		}
	}