<?php
/* Address Test cases generated on: 2010-03-13 11:03:14 : 1268471054*/
App::uses('ContactAddress', 'Contact.Model');

class ContactAddressTest extends CakeTestCase {

	public $fixtures = array('plugin.contact.contact_address');

	public function startTest() {
		$this->ContactAddress = ClassRegistry::init('Contact.ContactAddress');
	}

	public function testDummy() {}

	public function endTest() {
		unset($this->ContactAddress);
		ClassRegistry::flush();
	}
}