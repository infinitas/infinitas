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
 * @brief test if the event class has the required event
 *
 * @param string $event the event to check for
 *
 * @return boolean
 */
	protected function _hasTrigger($event) {
		$eventClass = new ReflectionClass(get_class($this->EventClass));
		$parentMethods = $eventClass->getParentClass()->getMethods(ReflectionMethod::IS_PUBLIC);

		foreach($parentMethods as $parentMethod) {
			$declaringClass = $eventClass->getMethod($parentMethod->getName())->getDeclaringClass()->getName();
			if($declaringClass === $eventClass->getName() && $parentMethod->getName() == 'on' . ucfirst($event)) {
				return true;
			}
		}

		return false;
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
		if(!$this->_hasTrigger('pluginRollCall')) {
			return false;
		}

		$expected = $this->_manualCall('pluginRollCall');

		$result = $this->Event->trigger($this->ObjectObject, $this->plugin . '.pluginRollCall');
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting additional db configs
 */
	public function testRequireDatabaseConfigs() {
		if(!$this->_hasTrigger('requireDatabaseConfigs')) {
			return false;
		}

		$expected = $this->_manualCall('requireDatabaseConfigs', $this->ObjectEvent);

		$result = $this->Event->trigger($this->ModelObject, $this->plugin . '.requireDatabaseConfigs');
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting the admin menu
 */
	public function testAdminMenu() {
		if(!$this->_hasTrigger('adminMenu')) {
			return false;
		}

		$expected = $this->_manualCall('adminMenu', $this->ObjectEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.adminMenu');
		$this->assertEquals($expected, $result);
	}

/**
 *@brief test required helpers load correctly
 */
	public function testRequireHelpers() {
		if(!$this->_hasTrigger('requireHelpersToLoad')) {
			return false;
		}

		$expected = $this->_manualCall('requireHelpersToLoad', $this->ViewtEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.requireHelpersToLoad');
		$this->assertEquals($expected, $result);
	}

/**
 *@brief test required helpers load correctly
 */
	public function testRequireComponents() {
		if(!$this->_hasTrigger('requireComponentsToLoad')) {
			return false;
		}

		$expected = $this->_manualCall('requireComponentsToLoad', $this->ViewtEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.requireComponentsToLoad');
		$this->assertEquals($expected, $result);
	}

/**
 *@brief test required helpers load correctly
 */
	public function testRequireCss() {
		if(!$this->_hasTrigger('requireCssToLoad')) {
			return false;
		}

		$expected = $this->_manualCall('requireCssToLoad', $this->ViewtEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.requireCssToLoad');
		$this->assertEquals($expected, $result);
	}

/**
 *@brief test required helpers load correctly
 */
	public function testRequireJs() {
		if(!$this->_hasTrigger('requireJavascriptToLoad')) {
			return false;
		}

		$expected = $this->_manualCall('requireJavascriptToLoad', $this->ViewtEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.requireJavascriptToLoad');
		$this->assertEquals($expected, $result);
	}

/**
 *@brief test required helpers load correctly
 */
	public function testUserProfile() {
		if(!$this->_hasTrigger('userProfile')) {
			return false;
		}

		$expected = $this->_manualCall('userProfile', $this->ViewtEvent);

		$result = $this->Event->trigger($this->ViewObject, $this->plugin . '.userProfile');
		$this->assertEquals($expected, $result);
	}
}