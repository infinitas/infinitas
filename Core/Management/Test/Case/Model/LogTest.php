<?php
App::uses('Log', 'Management.Model');

class LogTestCase extends CakeTestCase {

	public $fixtures = array('plugin.management.log');

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Log = ClassRegistry::init('Management.Log');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Log);
	}

	public function testSomething() {

	}

}