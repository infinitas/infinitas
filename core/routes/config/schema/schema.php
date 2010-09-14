<?php 
/* SVN FILE: $Id$ */
/* Routes schema generated on: 2010-09-14 21:09:53 : 1284492533*/
class RoutesSchema extends CakeSchema {
	var $name = 'Routes';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $routes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'url' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'prefix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'values' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'rules' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'theme_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>