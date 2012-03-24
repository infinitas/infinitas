<?php
/* Contacts Test cases generated on: 2010-12-14 13:12:08 : 1292334968*/
App::uses('ContactsHelper', 'Contact.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class ContactsHelperTest extends CakeTestCase {
	function startTest() {
		$this->Contacts = new ContactsHelper(new View(new Controller()));
	}

	function testDummy() {}

	function endTest() {
		unset($this->Contacts);
		ClassRegistry::flush();
	}

}