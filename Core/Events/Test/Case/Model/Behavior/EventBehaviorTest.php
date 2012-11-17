<?php
App::uses('Model', 'Model');
class DummyModel extends Model {
	public $useTable = false;
}

class EventBehaviorTest extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->DummyModel = new DummyModel();
		$this->DummyModel->Behaviors->attach('Events.Event');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Event);
	}

	public function testEvent() {
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