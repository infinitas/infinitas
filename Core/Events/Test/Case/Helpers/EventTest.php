<?php
	/* Event Test cases generated on: 2010-12-14 22:12:39 : 1292364399*/
	App::import('Helper', 'Events.Event');

	class EventHelperTestCase extends CakeTestCase {
		function startTest() {
			$this->Event = new EventHelper();
		}

		function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testEvents(){
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->Event->trigger('foo'));
			$this->assertEqual($expected, $this->Event->trigger('plugin.foo'));

			$global = $this->Event->trigger('returnEventForTest');
			$plugin = $this->Event->trigger('events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIdentical(
				$global['returnEventForTest']['events']->Handler,
				$plugin['returnEventForTest']['events']->Handler
			);
			$this->assertIdentical($global['returnEventForTest']['events']->Handler, $this->Event);
			$this->assertIdentical($plugin['returnEventForTest']['events']->Handler, $this->Event);
		}
	}