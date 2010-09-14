<?php 
/* SVN FILE: $Id$ */
/* ViewCounter schema generated on: 2010-09-14 21:09:55 : 1284492535*/
class ViewCounterSchema extends CakeSchema {
	var $name = 'ViewCounter';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $view_counts = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 32),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'ip_address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>