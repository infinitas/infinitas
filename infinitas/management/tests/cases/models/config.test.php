<?php
/* Config Test cases generated on: 2010-03-13 11:03:23 : 1268471123*/
App::import('Model', 'management.Config');

/**
 * ConfigTestCase
 *
 * @package
 * @author dogmatic
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class ConfigTestCase extends CakeTestCase {
	var $fixtures = array(
		'plugin.management.config'
	);

	function startTest() {
		$this->Config =& ClassRegistry::init('Config');
	}

	/**
	 * Test custom validation rules
	 *
	 * Check that the correct options use the corect values.
	 */
	function testValidationRules(){
		$this->assertTrue($this->Config->customOptionCheck(array('options' => 'no type set yet so dont validate')));
		/**
		 * test string inputs
		 */
		$this->Config->data['Config']['type'] = 'string';
		$data['options'] = 'abc';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 123;
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = null;
		$this->assertTrue($this->Config->customOptionCheck($data));

		/**
		 * test integer inputs
		 */
		$this->Config->data['Config']['type'] = 'integer';
		$data['options'] = null;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = 'text';
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = 123;
		$this->assertTrue($this->Config->customOptionCheck($data));

		/**
		 * test dropdowns inputs
		 */
		$this->Config->data['Config']['type'] = 'dropdown';
		$data['options'] = null;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = 'text';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 'option1,option2,option3,option4';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 'option1, option2, option3, option4';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 'option1 ,option2 ,option3 ,option4';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 'option1 ,option2, option3 ,option4';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 123;
		$this->assertFalse($this->Config->customOptionCheck($data));

		/**
		 * test bool
		 */
		$this->Config->data['Config']['type'] = 'bool';
		$data['options'] = null;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = true;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = false;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$data['options'] = 'true,false';
		$this->assertTrue($this->Config->customOptionCheck($data));

		$data['options'] = 'false,true';
		$this->assertTrue($this->Config->customOptionCheck($data));

		/**
		 * test array
		 */
		$this->Config->data['Config']['type'] = 'array';
		$data = array();

		$this->Config->data['Config']['value'] = null;
		$this->assertFalse($this->Config->customOptionCheck($data));

		$this->Config->data['Config']['value'] = '{}';
		$this->assertFalse($this->Config->customOptionCheck($data));

		$this->Config->data['Config']['value'] = 'some text';
		$this->assertFalse($this->Config->customOptionCheck($data));

		// see more tests for checking json in the app model tests
		$this->Config->data['Config']['value'] = '{"hello":"world"}';
		$this->assertTrue($this->Config->customOptionCheck($data));
	}

	/**
	 * Test geting config values.
	 *
	 * Make sure the values put in config is correct
	 *
	 * Need to bypass cache.
	 */
	function testGetConfigs(){
		//check the cache
		$data = $this->Config->getConfig();
		unlink(CACHE.'core'.DS.'configs');


		$data = Set::extract('/Config/key', $this->Config->getInstallSetupConfigs());
		$this->assertEqual($data, array(0 => 'Website.description', 1 => 'Website.name'));
	}

	function endTest() {
		unset($this->Config);
		ClassRegistry::flush();
	}

}
?>