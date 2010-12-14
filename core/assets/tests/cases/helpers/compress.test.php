<?php
	App::import('Helper', 'Assets.Compress');

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