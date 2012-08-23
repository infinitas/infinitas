<?php
/* IpAddress Test cases generated on: 2010-03-13 11:03:42 : 1268471142*/
App::uses('IpAddress', 'Management.Model');

class IpAddressTest extends CakeTestCase {

	public $fixtures = array(
		'plugin.security.ip_address'
	);

	public function setUp() {
		parent::setUp();
		$this->IpAddress = ClassRegistry::init('Security.IpAddress');
	}

	public function testObject() {
		$this->assertIsA($this->IpAddress, 'IpAddress');
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->IpAddress);
	}
}