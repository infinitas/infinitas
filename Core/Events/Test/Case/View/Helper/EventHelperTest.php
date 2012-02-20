<?php
	/* Event Test cases generated on: 2010-12-14 22:12:39 : 1292364399*/
	App::uses('EventHelper', 'Events.View/Helper');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class EventHelperTest extends CakeTestCase {
		function startTest() {
			$this->Event = new EventHelper(new View(new Controller()));
		}

		function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testEvents(){
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->Event->trigger('foo'));
			$this->assertEqual($expected, $this->Event->trigger('Plugin.foo'));

			$global = $this->Event->trigger('returnEventForTest');
			$plugin = $this->Event->trigger('Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIdentical(
				$global['returnEventForTest']['Events']->Handler,
				$plugin['returnEventForTest']['Events']->Handler
			);
			$this->assertIdentical($global['returnEventForTest']['Events']->Handler, $this->Event);
			$this->assertIdentical($plugin['returnEventForTest']['Events']->Handler, $this->Event);
		}
	}