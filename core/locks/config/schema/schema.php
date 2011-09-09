<?php 
/* SVN FILE: $Id$ */
/* Locks schema generated on: 2011-09-09 13:09:50 : 1315570730*/
class LocksSchema extends CakeSchema {
	var $name = 'Locks';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $locks = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'lock' => array('column' => array('class', 'foreign_key'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>