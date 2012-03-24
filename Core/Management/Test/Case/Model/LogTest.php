<?php
/* Log Test cases generated on: 2010-03-13 11:03:55 : 1268471155*/
App::uses('Log', 'Management.Model');

class LogTestCase extends CakeTestCase {

	public $fixtures = array('plugin.management.log');

	function startTest() {
		$this->Log = ClassRegistry::init('Management.Log');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Log);
		ClassRegistry::flush();
	}

}