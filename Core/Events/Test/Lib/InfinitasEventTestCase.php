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

		$this->ModelObject = $this->ViewObject = $this->ControllerObject = new Object();

		$this->ObjectEvent = new Event('TestEvent', new Object(), $this->plugin);
		$this->ModelEvent = new Event('ModelEvent', $this->ModelObject, $this->plugin);
		$this->ViewtEvent = new Event('ViewtEvent', $this->ViewObject, $this->plugin);
		$this->ControllerEvent = new Event('ControllerEvent', $this->ControllerObject, $this->plugin);

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
		//App::uses($this->plugin . 'Events', $this->plugin . '.Lib');

		$this->EventClass = $this->plugin . 'Events';
		$this->EventClass = new $this->EventClass();
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
		$expected = array('pluginRollCall' => array($this->EventClass->onPluginRollCall()));
		$expected['pluginRollCall'] = array_filter($expected['pluginRollCall']);
		$result = $this->Event->trigger(new Object(), $this->plugin . '.pluginRollCall');
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting additional db configs
 */
	public function testRequireDatabaseConfigs() {
		$expected = array('requireDatabaseConfigs' => array($this->EventClass->onRequireDatabaseConfigs($this->ModelEvent)));
		$expected['requireDatabaseConfigs'] = array_filter($expected['requireDatabaseConfigs']);
		$result = $this->Event->trigger(new Object(), $this->plugin . '.requireDatabaseConfigs');
		$this->assertEquals($expected, $result);
	}
}