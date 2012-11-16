<?php
App::uses('ContactsHelper', 'Contact.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class ContactsHelperTest extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Contacts = new ContactsHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Contacts);
	}

	public function testSomething() {

	}

}