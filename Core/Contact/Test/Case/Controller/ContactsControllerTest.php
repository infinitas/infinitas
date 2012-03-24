<?php
/* Contacts Test cases generated on: 2010-12-14 13:12:41 : 1292334941*/
App::uses('ContactsController', 'Contact.Controller');

class TestContactsController extends ContactsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ContactsControllerTest extends CakeTestCase {

	public $fixtures = array('plugin.configs.config');

	function startTest() {
		$this->Contacts = new TestContactsController();
		$this->Contacts->constructClasses();
	}

	function testDummy() {}

	function endTest() {
		unset($this->Contacts);
		ClassRegistry::flush();
	}

}