<?php 
/* SVN FILE: $Id$ */
/* Modules schema generated on: 2010-09-18 18:09:21 : 1284828621*/
class ModulesSchema extends CakeSchema {
	var $name = 'Modules';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $module_positions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $modules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'config' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'theme_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'show_heading' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'module_loader_by_position' => array('column' => array('position_id', 'admin', 'active', 'ordering'), 'unique' => 0)),
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