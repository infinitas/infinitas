<?php
App::uses('Contact', 'Contact.Model');

class ContactTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contact.contact'
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Contact = ClassRegistry::init('Contact.Contact');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Contact);
	}

	public function testSomething() {
	}
}