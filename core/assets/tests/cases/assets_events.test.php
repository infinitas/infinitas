<?php	
	class AssetsEventsTestCase extends CakeTestCase {
		public function startTest() {
			$this->Event = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testStuff(){
			$this->assertIsA($this->Event, 'EventCore');

			$expected = array('requireHelpersToLoad' => array('assets' => array('Assets.Compress')));
			$this->assertEqual($expected, $this->Event->trigger(new Object(), 'assets.requireHelpersToLoad'));
		}
	}