<?php
/* Group Test cases generated on: 2010-03-13 11:03:33 : 1268471133*/
App::uses('Group', 'Users.Model');

class GroupTestCase extends CakeTestCase {

	public $fixtures = array(
		'plugin.users.group'
	);

	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Users.Group');
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Group);
	}

	public function testSomething() {

	}

}