<?php
	App::import('Libs', 'Events.Event');
	
	class EventsTest extends CakeTestCase {
		public function startTest(){
			$this->Events = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Events);
			ClassRegistry::flush();
		}

		public function testGetEventInstance(){
			$this->Events->something = 'foo';
			$Event = EventCore::getInstance();
			$this->assertTrue(isset($Event->something));
			$this->assertTrue($Event->something == 'foo');

			unset($Event);
			$this->assertFalse(isset($this->Event));
		}

		public function testPluginsWith(){
			$this->assertEqual(array(), $this->Events->pluginsWith('foo'));
			$this->assertEqual(array('events'), $this->Events->pluginsWith('returnEventForTest'));
		}

		public function testEventClass(){
			$Event = new Event('someEvent', $this, 'myPlugin');
			$this->assertIdentical($this, $Event->Handler);
			$this->assertEqual('someEvent', $Event->name);
			$this->assertEqual('myPlugin', $Event->plugin);
		}

		public function testEvents(){
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->Events->trigger($this, 'foo'));
			$this->assertEqual($expected, $this->Events->trigger($this, 'plugin.foo'));
			
			$global = $this->Events->trigger($this, 'returnEventForTest');
			$plugin = $this->Events->trigger($this, 'events.returnEventForTest');
			
			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIdentical(
				$global['returnEventForTest']['events']->Handler,
				$plugin['returnEventForTest']['events']->Handler
			);
			$this->assertIdentical($global['returnEventForTest']['events']->Handler, $this);
			$this->assertIdentical($plugin['returnEventForTest']['events']->Handler, $this);
		}

		public function testLoadEventClass(){
		}
	}