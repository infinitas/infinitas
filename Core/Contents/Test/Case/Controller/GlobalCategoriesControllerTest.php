<?php
	/* Categories Test cases generated on: 2010-12-14 01:12:59 : 1292289659*/
	App::uses('GlobalCategoriesController', 'Contents.Controller');

	class TestCategoriesController extends GlobalCategoriesController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class GlobalCategoriesControllerTest extends CakeTestCase {

		public $fixtures = array('plugin.configs.config');

		function startTest() {
			$this->Categories = new TestCategoriesController();
			$this->Categories->constructClasses();
		}

		function testDummy() {}

		function endTest() {
			unset($this->Categories);
			ClassRegistry::flush();
		}
	}