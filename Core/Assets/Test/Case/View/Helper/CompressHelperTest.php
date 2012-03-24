<?php
	App::uses('CompressHelper', 'Assets.View/Helper');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class CompressHelperTest extends CakeTestCase {
		public function startTest() {
			$this->Compress = new CompressHelper(new View(new Controller()));
		}

		public function endTest() {
			unset($this->Compress);
			ClassRegistry::flush();
		}

		public function testStuff(){
			$this->assertIsA($this->Compress, 'CompressHelper');
		}
	}