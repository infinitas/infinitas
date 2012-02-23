<?php
/* Branch Test cases generated on: 2010-12-14 13:12:22 : 1292334982*/
App::uses('Branch', 'Contact.Model');

class BranchTest extends CakeTestCase {

	public $fixtures = array('plugin.contact.branch');

	function startTest() {
		$this->Branch =& ClassRegistry::init('Contact.Branch');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Branch);
		ClassRegistry::flush();
	}

}