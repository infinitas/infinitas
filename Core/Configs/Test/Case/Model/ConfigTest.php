<?php
App::uses('Config', 'Configs.Model');

/**
 * ConfigTestCase
 *
 * @package
 * @author dogmatic
 * @copyright Copyright (c) 2010
 * @version $Id$
 */
class ConfigTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.configs.config'
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Config = ClassRegistry::init('Configs.Config');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Config);
	}

/**
 * Test custom validation rules
 *
 * Check that the correct options use the corect values.
 */
	public function testValidationRules() {
		$data = array();
		$result = $this->Config->validateCustomOptionCheck(array('options' => 'no type set yet so dont validate'));
		$this->assertTrue($result);

		// test string inputs
		$this->Config->data['Config']['type'] = 'string';
		$data['options'] = 'abc';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 123;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = null;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		// test integer inputs
		$this->Config->data['Config']['type'] = 'integer';
		$data['options'] = null;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = 'text';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = 123;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		// test dropdowns inputs
		$this->Config->data['Config']['type'] = 'dropdown';
		$data['options'] = null;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = 'text';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 'option1,option2,option3,option4';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 'option1, option2, option3, option4';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 'option1 ,option2 ,option3 ,option4';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 'option1 ,option2, option3 ,option4';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 123;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		// test bool
		$this->Config->data['Config']['type'] = 'bool';
		$data['options'] = null;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = true;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = false;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$data['options'] = 'true,false';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		$data['options'] = 'false,true';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);

		// test array
		$this->Config->data['Config']['type'] = 'array';
		$data = array();

		$this->Config->data['Config']['value'] = null;
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$this->Config->data['Config']['value'] = '{}';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		$this->Config->data['Config']['value'] = 'some text';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertFalse($result);

		// see more tests for checking json in the app model tests
		$this->Config->data['Config']['value'] = '{"hello":"world"}';
		$result = $this->Config->validateCustomOptionCheck($data);
		$this->assertTrue($result);
	}

/**
 * Test geting config values.
 *
 * Make sure the values put in config is correct
 *
 * Need to bypass cache.
 */
	public function testGetConfigs() {
		// value format
		$result = $this->Config->getConfig();
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
		$this->assertEquals($expected, $result);

		// getting website configs for installer
		$result = $this->Config->getInstallSetupConfigs();
		$expected = array(
			array(
				'Config' => array(
					'id' => '9',
					'key' => 'Website.description',
					'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
					'type' => 'string',
					'options' => '',
					'created' => null,
					'modified' => null
				)
			),
			array(
				'Config' => array(
					'id' => '8',
					'key' => 'Website.name',
					'value' => 'Infinitas Cms',
					'type' => 'string',
					'options' => '',
					'created' => null,
					'modified' => null
				)
			)
		);
		$this->assertEquals($expected, $result);
	}
/**
 * test cache is cleared after calling save
 */
	public function testCacheRelatedStuff() {
		$this->skipIf(true);
		$result = Cache::read('global_configs');
		$this->assertTrue($result, 'There is nothing in the cache');
		$cacheConfigs = $this->Config->getConfig();

		$this->assertTrue($this->Config->afterSave(true));
		$result = Cache::read('global_configs');
		$this->assertFalse($result);

		$nonCacheConfigs = $this->Config->getConfig();
		$result = Cache::read('global_configs');
		$this->assertTrue($result, 'Cache was not created');

		$this->assertEquals($cacheConfigs, $nonCacheConfigs);
	}

/**
 * test formatting of values for configuration
 */
	public function testFormatting() {
		$this->Config->afterSave(true);
		$configs = $this->Config->getConfig(true);
		$expect = array();
		$expect['Test.bool_false'] = false;
		$expect['Test.bool_true'] = true;
		$expect['Test.int_normal'] = 123;
		$expect['Test.int_string'] = 987;
		$expect['Test.nested_array'] = array('abc1' => array('abc2' => array('abc3' => array('abc4' => array('abc5' => 'xyz')))));
		$expect['Test.simple_array'] = array('abc' => 'xyz');
		$expect['Test.string1'] = 'this is a string';
		$expect['Website.description'] = 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.';
		$expect['Website.name'] = 'Infinitas Cms';
		$this->assertEquals($expect, $configs);

		$configs = $this->Config->getConfig(true);
		$this->assertEquals($expect, $configs);
	}
}