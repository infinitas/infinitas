<?php
	/* Event Test cases generated on: 2010-12-14 17:12:02 : 1292349062*/
	App::import('Controller', 'Controller', false);
	App::import('Component', 'Events.Event');

	class DummyController extends Controller{
		public $uses = array();
		public $components = array('Events.Event');
	}

	class EventComponentTestCase extends CakeTestCase {
		function startTest() {
			$this->DummyController = new DummyController();
			$this->DummyController->Event = new EventComponent();
			$this->DummyController->Event->initialize($this->DummyController);
		}

		function endTest() {
			unset($this->EventComponent);
			ClassRegistry::flush();
		}

		function testEvents(){
			$expected = array('foo'=> array());
			$this->assertEqual($expected, $this->DummyController->Event->trigger('foo'));
			$this->assertEqual($expected, $this->DummyController->Event->trigger('plugin.foo'));

			$global = $this->DummyController->Event->trigger('returnEventForTest');
			$plugin = $this->DummyController->Event->trigger('events.returnEventForTest');

			/**
			 * test calling the plugin method vs global returns the same format.
			 */
			$this->assertIsA($global['returnEventForTest']['events']->Handler, 'DummyController');
			$this->assertIsA($plugin['returnEventForTest']['events']->Handler, 'DummyController');
		}
	}