<?php
	/* Event Test cases generated on: 2010-12-14 17:12:02 : 1292349062*/
	App::uses('Controller', 'Controller');
	App::uses('EventComponent', 'Events.Controller/Component');

	class DummyController extends Controller{
		public $uses = array();
		public $components = array('Events.Event');
	}

	class EventComponentTest extends CakeTestCase {
		function startTest() {
			$this->DummyController = new DummyController();
			$this->DummyController->Event = new EventComponent(new ComponentCollection);
			$this->DummyController->Event->initialize($this->DummyController);
		}

		function endTest() {
			unset($this->EventComponent);
			ClassRegistry::flush();
		}

		function testEvents() {
			$result = $this->DummyController->Event->trigger('foo');
			$expected = array('foo'=> array());
			$this->assertEquals($expected, $result);

			$result = $this->DummyController->Event->trigger('Plugin.foo');
			$this->assertEquals($expected, $result);

			$global = $this->DummyController->Event->trigger('returnEventForTest');
			$plugin = $this->DummyController->Event->trigger('Events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertInstanceOf('DummyController', $global['returnEventForTest']['Events']->Handler);
			$this->assertInstanceOf('DummyController', $plugin['returnEventForTest']['Events']->Handler);
		}
	}