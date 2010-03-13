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
		// value format
		$expected = array(
			array('Config' => array('key' => 'Test.bool_false', 'value' => false, 'type' => 'bool')),
			array('Config' => array('key' => 'Test.bool_true', 'value' => true, 'type' => 'bool')),
			array('Config' => array('key' => 'Test.int_normal', 'value' => 123, 'type' => 'integer')),
			array('Config' => array('key' => 'Test.int_string', 'value' => 987, 'type' => 'integer')),
			array('Config' => array('key' => 'Test.nested_array',
					'value' => array('abc1' => array('abc2' => array(
						'abc3' => array('abc4' => array('abc5' => 'xyz'))))),
					'type' => 'array')),
			array('Config' => array('key' => 'Test.simple_array', 'value' => array('abc' => 'xyz'), 'type' => 'array')),
			array('Config' => array('key' => 'Test.string1', 'value' => 'this is a string', 'type' => 'string')),
			array('Config' => array('key' => 'Website.description',
					'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
					'type' => 'string')),
			array('Config' => array('key' => 'Website.name', 'value' => 'Infinitas Cms', 'type' => 'string')));
		$this->assertEqual($this->Config->getConfig(), $expected);

		// getting website configs for installer
		$expected = array(
			array(
				'Config' => array(
					'id' => 9, 'key' => 'Website.description',
					'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
					'type' => 'string', 'options' => null,
					'description' => 'This is the main description about the site', 'core' => 0
				)
			),
			array(
				'Config' => array(
					'id' => 8, 'key' => 'Website.name', 'value' => 'Infinitas Cms',
					'type' => 'string', 'options' => null,
					'description' => '<p>This is the name of the site that will be used in emails and on the website its self</p>',
					'core' => 0
				)
			)
		);
		$this->assertEqual($this->Config->getInstallSetupConfigs(), $expected);
	}

	function endTest() {
		unset($this->Config);
		ClassRegistry::flush();
	}

}
?>