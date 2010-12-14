<?php
	App::import('AppController');
	App::import('Categories.CategoriesAppController');
	class CategoriesAppControllerTestCase extends CakeTestCase {
		function startTest() {
			$this->CategoryAppController = new CategoriesAppController();
		}

		function testStuff(){
			$this->assertIsA($this->CategoryAppController, 'CategoriesAppController');
		}

		function endTest() {
			unset($this->CategoryAppController);
			ClassRegistry::flush();
		}
	}