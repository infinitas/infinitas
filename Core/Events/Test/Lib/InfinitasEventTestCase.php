<?php
/**
 * @brief base test class for testing Events
 */
class InfinitasEventTestCase extends CakeTestCase {
/**
 * @brief set up required objects for testing
 *
 * @todo setup MVC objects to pass in the correct places
 */
	public function setUp() {
		parent::setUp();
		$this->_setPlugin();

		$this->ObjectObject = $this->ModelObject = $this->ViewObject = $this->ControllerObject = new Object();

		$this->ObjectEvent = new Event('TestEvent', $this->ObjectObject, $this->plugin);
		$this->ModelEvent = new Event('ModelEvent', $this->ModelObject, $this->plugin);
		$this->ViewtEvent = new Event('ViewtEvent', $this->ViewObject, $this->plugin);
		$this->ControllerEvent = new Event('ControllerEvent', $this->ControllerObject, $this->plugin);

		EventCore::loadEventHandler($this->plugin);

		$this->Event = EventCore::getInstance();

		$this->_loadEventClass();
	}

/**
 * @brief clear up the enviroment
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Event, $this->EventClass);
		unset($this->ObjectEvent, $this->ControllerEvent, $this->ModelEvent, $this->ViewtEvent);
		unset($this->ModelObject, $this->ViewObject, $this->ControllerObject);
	}

/**
 * @brief setup the current plugin name
 */
	protected function _setPlugin() {
		if(!isset($this->plugin)) {
			$this->plugin = str_replace('EventsTest', '', get_class($this));
		}
	}

/**
 * @brief load and get an instance of the event class being tested
 */
	protected function _loadEventClass() {
		App::uses($this->plugin . 'Events', $this->plugin . '.Lib');

		$this->EventClass = $this->plugin . 'Events';
		$this->EventClass = new $this->EventClass();
	}

/**
 * @brief manuall call an event
 *
 * This will manually call an event to get the return, for auto testing of triggers
 * that do not use complex checks, just standard returns.
 *
 * @param string $event the name of the event
 * @param Event $object the event object that would be passed
 *
 * @return array
 */
	protected function _manualCall($event, $object = null) {
		$method = 'on' . ucfirst($event);
		$expected = array($event => array(call_user_func_array(array($this->EventClass, $method), array($object))));
		$return = current(array_filter($expected[$event]));
		$expected[$event] = array();
		if($return) {
			$expected[$event] = array($this->plugin => $return);
		}

		return $expected;
	}

/**
 * @brief test the instance is loaded correctly
 */
	public function testInstance() {
		$this->assertInstanceOf('EventCore', $this->Event);
	}

/**
 * @brief test getting the plugins details
 */
	public function testPluginRollCall() {
		$expected = $this->_manualCall('pluginRollCall');

		$result = $this->Event->trigger($this->ObjectObject, $this->plugin . '.pluginRollCall');
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting additional db configs
 */
	public function testRequireDatabaseConfigs() {
		$expected = $this->_manualCall('requireDatabaseConfigs', $this->ObjectEvent);

		$result = $this->Event->trigger($this->ModelObject, $this->plugin . '.requireDatabaseConfigs');
		$this->assertEquals($expected, $result);
	}
}