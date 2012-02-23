<?php
	class AssetsEventsTest extends CakeTestCase {
		public function startTest() {
			$this->Event = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testStuff(){
			$this->assertInstanceOf('EventCore', $this->Event);

			$expected = array('requireHelpersToLoad' => array('Assets' => array('Assets.Compress')));
			$result = $this->Event->trigger(new Object(), 'Assets.requireHelpersToLoad');
			$this->assertEquals($expected, $result);
		}
	}