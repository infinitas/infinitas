<?php
/* CoreMenuItem Fixture generated on: 2010-03-13 11:03:55 : 1268472295 */
class MenuItemFixture extends CakeTestFixture {
	var $name = 'MenuItem';

	var $table = 'core_menu_items';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'link' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'params' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0), 'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>