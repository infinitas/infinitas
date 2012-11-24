<?php
/**
 * fixture file for Config tests.
 *
 * @package Configs.Fixture
 * @since 0.9b1
 */
class ConfigFixture extends CakeTestFixture {

	public $name = 'Config';

	public $table = 'core_configs';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'options' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'config_key' => array('column' => 'key', 'unique' => 1),
			'key' => array('column' => 'key', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'key' => 'Test.string1',
			'value' => 'this is a string',
			'type' => 'string',
			'options' => '0,1,2,3',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 2,
			'key' => 'Test.bool_true',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 3,
			'key' => 'Test.bool_false',
			'value' => 'false',
			'type' => 'bool',
			'options' => 'false,true',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 4,
			'key' => 'Test.int_normal',
			'value' => 123,
			'type' => 'integer',
			'options' => '',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 5,
			'key' => 'Test.int_string',
			'value' => '987',
			'type' => 'integer',
			'options' => '',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 6,
			'key' => 'Test.simple_array',
			'value' => '{"abc":"xyz"}',
			'type' => 'array',
			'options' => '',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 7,
			'key' => 'Test.nested_array',
			'value' => '{"abc1":{"abc2":{"abc3":{"abc4":{"abc5":"xyz"}}}}}',
			'type' => 'array',
			'options' => '',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 8,
			'key' => 'Website.name',
			'value' => 'Infinitas Cms',
			'type' => 'string',
			'options' => '',
			'created' => null,
			'modified' => null
		),
		array(
			'id' => 9,
			'key' => 'Website.description',
			'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
			'type' => 'string',
			'options' => '',
			'created' => null,
			'modified' => null
		),
	);
}