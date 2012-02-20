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
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->DummyModel->triggerEvent('foo'));
			$this->assertEqual($expected, $this->DummyModel->triggerEvent('Plugin.foo'));

			$global = $this->DummyModel->triggerEvent('returnEventForTest');
			$plugin = $this->DummyModel->triggerEvent('Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIsA($global['returnEventForTest']['Events']->Handler, 'DummyModel');
			$this->assertIsA($plugin['returnEventForTest']['Events']->Handler, 'DummyModel');
		}
	}