<?php
	App::uses('CompressHelper', 'Assets.View/Helper');

	class CompressHelperTestCase extends CakeTestCase {
		public function startTest() {
			$this->Compress =& new CompressHelper();
		}

		public function endTest() {
			unset($this->Compress);
			ClassRegistry::flush();
		}

		public function testStuff(){
			$this->assertIsA($this->Compress, 'CompressHelper');
		}
	}