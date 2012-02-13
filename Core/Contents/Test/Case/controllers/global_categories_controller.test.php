<?php
	/* Categories Test cases generated on: 2010-12-14 01:12:59 : 1292289659*/
	App::import('Controller', 'Categories.Categories');

	class TestCategoriesController extends CategoriesController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class CategoriesControllerTestCase extends CakeTestCase {
		function startTest() {
			$this->Categories = new TestCategoriesController();
			$this->Categories->constructClasses();
		}

		function endTest() {
			unset($this->Categories);
			ClassRegistry::flush();
		}
	}