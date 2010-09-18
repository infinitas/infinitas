<?php 
/* SVN FILE: $Id$ */
/* Installer schema generated on: 2010-09-18 18:09:21 : 1284828621*/
class InstallerSchema extends CakeSchema {
	var $name = 'Installer';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $plugins = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'internal_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'website' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'update_url' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'license' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75),
		'dependancies' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'version' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10),
		'enabled' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'update_url' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'internal_name' => array('column' => 'internal_name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
?>