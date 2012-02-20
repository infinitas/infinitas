<?php
	App::uses('EventCore', 'Events.Lib');

	class EventCoreTest extends CakeTestCase {
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
			$this->assertEqual(array('Events'), $this->Events->pluginsWith('returnEventForTest'));
		}

		public function testEventClass(){
			$Event = new Event('someEvent', $this, 'MyPlugin');
			$this->assertIdentical($this, $Event->Handler);
			$this->assertEqual('someEvent', $Event->name);
			$this->assertEqual('MyPlugin', $Event->plugin);
		}

		public function testEvents(){
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->Events->trigger($this, 'foo'));
			$this->assertEqual($expected, $this->Events->trigger($this, 'Plugin.foo'));

			$global = $this->Events->trigger($this, 'returnEventForTest');
			$plugin = $this->Events->trigger($this, 'Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIdentical(
				$global['returnEventForTest']['Events']->Handler,
				$plugin['returnEventForTest']['Events']->Handler
			);
			$this->assertIdentical($global['returnEventForTest']['Events']->Handler, $this);
			$this->assertIdentical($plugin['returnEventForTest']['Events']->Handler, $this);
		}

		public function testLoadEventClass(){
		}
	}