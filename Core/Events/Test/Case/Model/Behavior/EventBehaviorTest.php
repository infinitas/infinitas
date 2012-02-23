<?php
	/* Event Test cases generated on: 2010-12-14 22:12:32 : 1292364992*/
	App::uses('Model', 'Model');
	class DummyModel extends Model {
		public $useTable = false;
	}

	class EventBehaviorTest extends CakeTestCase {
		public function startTest() {
			$this->DummyModel = new DummyModel();
			$this->DummyModel->Behaviors->attach('Events.Event');
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testEvent(){
			$result = $this->DummyModel->triggerEvent('foo');
			$expected = array('foo'=> array());
			$this->assertEquals($expected, $result);

			$result = $this->DummyModel->triggerEvent('Plugin.foo');
			$this->assertEqual($expected, $result);

			$global = $this->DummyModel->triggerEvent('returnEventForTest');
			$plugin = $this->DummyModel->triggerEvent('Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertInstanceOf('DummyModel', $global['returnEventForTest']['Events']->Handler);
			$this->assertInstanceOf('DummyModel', $plugin['returnEventForTest']['Events']->Handler);
		}
	}