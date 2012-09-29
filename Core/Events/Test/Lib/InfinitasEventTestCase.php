<?php
/**
 * @brief base test class for testing Events
 */
class InfinitasEventTestCase extends CakeTestCase {
/**
 * @brief set up required objects for testing
 */
	public function startTest() {
		$this->Object = new Object();
		$this->Event = EventCore::getInstance();
	}

/**
 * @brief clear up the enviroment
 */
	public function endTest() {
		unset($this->Event, $this->Object);
		ClassRegistry::flush();
	}

/**
 * @brief test the instance is loaded correctly
 */
	public function testInstance() {
		$this->assertInstanceOf('EventCore', $this->Event);
	}
}