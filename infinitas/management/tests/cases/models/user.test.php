<?php
/* User Test cases generated on: 2010-03-11 18:03:16 : 1268325136*/
App::import('Model', 'Management.User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array(
		'plugin.management.user',
		'plugin.management.group',
		'plugin.management.aco', 'plugin.management.aro', 'plugin.management.aros_aco'
	);

	function startTest() {
		$this->User =& ClassRegistry::init('Management.User');
	}

	function testFind(){
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

}
?>