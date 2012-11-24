<?php
App::uses('ContactAddress', 'Contact.Model');

class ContactAddressTest extends CakeTestCase {

	public $fixtures = array('plugin.contact.contact_address');

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->ContactAddress = ClassRegistry::init('Contact.ContactAddress');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->ContactAddress);
	}

	public function testSomething() {
	}
}
