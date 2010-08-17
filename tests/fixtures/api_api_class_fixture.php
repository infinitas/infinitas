<?php
/* ApiApiClass Fixture generated on: 2010-08-17 14:08:37 : 1282055077 */
class ApiApiClassFixture extends CakeTestFixture {
	var $name = 'ApiApiClass';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'api_package_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'file_name' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'method_index' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'property_index' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'flags' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'coverage_cache' => array('type' => 'float', 'null' => false, 'default' => NULL, 'length' => '4,4'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'api_package_id' => array('column' => 'api_package_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>