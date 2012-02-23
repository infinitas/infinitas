<?php
/* IpAddress Test cases generated on: 2010-03-13 11:03:42 : 1268471142*/
App::uses('IpAddress', 'Management.Model');

class IpAddressTest extends CakeTestCase {

	public $fixtures = array('plugin.management.ip_address');

	function startTest() {
		$this->IpAddress =& ClassRegistry::init('Management.IpAddress');
	}

	function testDummy() {}

	function endTest() {
		unset($this->IpAddress);
		ClassRegistry::flush();
	}

}