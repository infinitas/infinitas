<?php
	class ChartsEventsTest extends CakeTestCase {
		public function startTest() {
			$this->Event = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testLibIsLoaded() {
			$this->assertIsA($this->Event, 'EventCore');
			$this->assertTrue(class_exists('ChartDataManipulation'));
		}
	}