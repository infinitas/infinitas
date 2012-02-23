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

			$result = $Event->something;
			$expected = 'foo';
			$this->assertEquals($expected, $result);

			unset($Event);
			$this->assertFalse(isset($this->Event));
		}

		public function testPluginsWith(){
			$result = $this->Events->pluginsWith('foo');
			$expected = array();
			$this->assertEquals($expected, $result);

			$result = $this->Events->pluginsWith('returnEventForTest');
			$expected = array('Events');
			$this->assertEquals($expected, $result);
		}

		public function testEventClass(){
			$Event = new Event('someEvent', $this, 'MyPlugin');
			$result = $Event->Handler;
			$this->assertSame($this, $result);

			$result = $Event->name;
			$expected = 'someEvent';
			$this->assertEquals($expected, $result);

			$result = $Event->plugin;
			$expected = 'MyPlugin';
			$this->assertEquals($expected, $result);
		}

		public function testEvents(){
			$result = $this->Events->trigger($this, 'foo');
			$expected = array('foo'=> array());
			$this->assertEquals($expected, $result);

			$result = $this->Events->trigger($this, 'Plugin.foo');
			$this->assertEqual($expected, $result);

			$global = $this->Events->trigger($this, 'returnEventForTest');
			$plugin = $this->Events->trigger($this, 'Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertSame(
				$global['returnEventForTest']['Events']->Handler,
				$plugin['returnEventForTest']['Events']->Handler
			);

			$result = $global['returnEventForTest']['Events']->Handler;
			$this->assertSame($this, $result);

			$result = $plugin['returnEventForTest']['Events']->Handler;
			$this->assertSame($this, $result);
		}

		public function testLoadEventClass(){
		}
	}