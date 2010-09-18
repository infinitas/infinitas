<?php 
/* SVN FILE: $Id$ */
/* Locks schema generated on: 2010-09-18 18:09:21 : 1284828621*/
class LocksSchema extends CakeSchema {
	var $name = 'Locks';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $locks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'key' => 'index'),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'lock' => array('column' => array('class', 'foreign_key'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>