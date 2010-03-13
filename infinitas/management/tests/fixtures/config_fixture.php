<?php
/* CoreConfig Fixture generated on: 2010-03-13 11:03:59 : 1268471759 */
class ConfigFixture extends CakeTestFixture {
	var $name = 'Config';

	var $table = 'core_configs';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'options' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'config_key' => array('column' => 'key', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'key' => 'Test.string1',
			'value' => 'this is a string',
			'type' => 'string',
			'options' => '0,1,2,3',
			'description' => 'testing string type',
			'core' => 0
		),
		array(
			'id' => 2,
			'key' => 'Test.bool_true',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'this is a bool and its true',
			'core' => 0
		),
		array(
			'id' => 3,
			'key' => 'Test.bool_false',
			'value' => 'false',
			'type' => 'bool',
			'options' => 'false,true',
			'description' => 'this is a bool and its false',
			'core' => 0
		),
		array(
			'id' => 4,
			'key' => 'Test.int_normal',
			'value' => 123,
			'type' => 'integer',
			'options' => '',
			'description' => 'this is a normal integer',
			'core' => 0
		),
		array(
			'id' => 5,
			'key' => 'Test.int_string',
			'value' => '987',
			'type' => 'integer',
			'options' => '',
			'description' => 'this is a string type integer',
			'core' => 0
		),
		array(
			'id' => 6,
			'key' => 'Test.simple_array',
			'value' => '{"abc":"xyz"}',
			'type' => 'array',
			'options' => '',
			'description' => 'this is a simple array',
			'core' => 0
		),
		array(
			'id' => 7,
			'key' => 'Test.nested_array',
			'value' => '{"abc1":{"abc2":{"abc3":{"abc4":{"abc5":"xyz"}}}}}',
			'type' => 'array',
			'options' => '',
			'description' => 'this is a nested array',
			'core' => 0
		),

		array(
			'id' => 8,
			'key' => 'Website.name',
			'value' => 'Infinitas Cms',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the name of the site that will be used in emails and on the website its self</p>',
			'core' => 0
		),
		array(
			'id' => 9,
			'key' => 'Website.description',
			'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
			'type' => 'string',
			'options' => '',
			'description' => 'This is the main description about the site',
			'core' => 0
		),
	);
}
?>