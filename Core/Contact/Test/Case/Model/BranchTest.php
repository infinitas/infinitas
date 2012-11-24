<?php
App::uses('Branch', 'Contact.Model');

class BranchTest extends CakeTestCase {

	public $fixtures = array('plugin.contact.branch');

/**
 * @brief set up at the start
 */
	public function setUp() {
		$this->Branch = ClassRegistry::init('Contact.Branch');
		parent::setUp();
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		unset($this->Branch);
		parent::tearDown();
	}

	public function testSomething() {
	}
}