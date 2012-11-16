<?php
	/* Event Test cases generated on: 2010-12-14 17:12:02 : 1292349062*/
	App::uses('Controller', 'Controller');
	App::uses('EventComponent', 'Events.Controller/Component');

	class DummyController extends Controller{
		public $uses = array();
		public $components = array('Events.Event');
	}

	class EventComponentTest extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->DummyController = new DummyController();
		$this->DummyController->Event = new EventComponent(new ComponentCollection);
		$this->DummyController->Event->initialize($this->DummyController);
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->EventComponent);
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