<?php
/* CoreMenuItem Fixture generated on: 2010-03-13 11:03:55 : 1268472295 */
class MenuItemFixture extends CakeTestFixture {
	var $name = 'MenuItem';

	var $table = 'core_menu_items';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'link' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'params' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0), 'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1, 'name' => 'Main_menu', 'slug' => 'main_menu', 'link' => '',
			'prefix' => '', 'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
			'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 0,
			'menu_id' => 1, 'group_id' => 0, 'parent_id' => NULL, 'lft' => 1, 'rght' => 8,
			'created' => '2010-09-30 22:41:15', 'modified' => '2010-09-30 22:41:15'
		),
			array(
				'id' => 2, 'name' => 'Blog',
				'slug' => 'blog', 'link' => '/', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'menu_id' => 1,'group_id' => 0, 'parent_id' => 1, 'lft' => 2, 'rght' => 3,
				'created' => '2010-09-30 22:42:49', 'modified' => '2010-09-30 22:42:49'
			),
			array(
				'id' => 3,'name' => 'Sandbox', 'slug' => 'sandbox', 'link' => '', 'prefix' => '',
				'plugin' => 'sandbox', 'controller' => 'sandbox', 'action' => 'index', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 1, 'class' => '', 'active' => 1,
				'menu_id' => 1, 'group_id' => 0, 'parent_id' => 1, 'lft' => 6, 'rght' => 7,
				'created' => '2010-11-09 14:09:01', 'modified' => '2010-11-09 14:09:01'
			),
			array(
				'id' => 4, 'name' => 'About Me',
				'slug' => 'about-me','link' => '/cms/random/about-me', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'menu_id' => 1, 'group_id' => 0, 'parent_id' => 1, 'lft' => 4, 'rght' => 5,
				'created' => '2010-11-09 22:47:09', 'modified' => '2010-11-09 22:47:09'
			),
		array(
			'id' => 5, 'name' => 'Main_menu', 'slug' => 'registered_users', 'link' => '',
			'prefix' => '', 'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
			'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 0,
			'menu_id' => 2, 'group_id' => 0, 'parent_id' => NULL, 'lft' => 9, 'rght' => 12,
			'created' => '2010-09-30 22:41:15', 'modified' => '2010-09-30 22:41:15'
		),
			array(
				'id' => 6, 'name' => 'About Me',
				'slug' => 'about-me','link' => '/cms/random/about-me', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'menu_id' => 2, 'group_id' => 0, 'parent_id' => 5, 'lft' => 10, 'rght' => 11,
				'created' => '2010-11-09 22:47:09', 'modified' => '2010-11-09 22:47:09'
			),
	);
}
?>