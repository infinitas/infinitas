<?php 
/* SVN FILE: $Id$ */
/* Users schema generated on: 2010-09-18 18:09:22 : 1284828622*/
class UsersSchema extends CakeSchema {
	var $name = 'Users';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'parent_id' => array('column' => 'parent_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'unique'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
		'facebook_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'twitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20, 'key' => 'index'),
		'birthday' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ip_address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'browser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'operating_system' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'last_click' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'is_mobile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1), 'email' => array('column' => 'email', 'unique' => 1), 'twitter_id' => array('column' => 'twitter_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>