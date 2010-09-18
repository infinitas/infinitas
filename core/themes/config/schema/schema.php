<?php 
/* SVN FILE: $Id$ */
/* Themes schema generated on: 2010-09-18 18:09:22 : 1284828622*/
class ThemesSchema extends CakeSchema {
	var $name = 'Themes';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $themes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'active' => array('column' => 'active', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>