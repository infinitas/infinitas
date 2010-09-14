<?php 
/* SVN FILE: $Id$ */
/* Modules schema generated on: 2010-09-14 21:09:52 : 1284492532*/
class ModulesSchema extends CakeSchema {
	var $name = 'Modules';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $module_positions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $modules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'config' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'theme_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_since' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'show_heading' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $modules_routes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'route_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>