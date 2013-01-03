<?php
/**
 * CoreMenuItemFixture
 *
 */
class CoreMenuItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'link' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'prefix' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'controller' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'params' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'force_backend' => array('type' => 'boolean', 'null' => true, 'default' => false),
		'force_frontend' => array('type' => 'boolean', 'null' => true, 'default' => false),

		'class' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'id_attribute' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'target' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'image' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),

		'menu_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'group_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0), 'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1, 'name' => 'Main_menu', 'slug' => 'main_menu', 'link' => '',
			'prefix' => '', 'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
			'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 0,
			'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
			'menu_id' => 'public-menu', 'group_id' => 0, 'parent_id' => null, 'lft' => 1, 'rght' => 8,
			'created' => '2010-09-30 22:41:15', 'modified' => '2010-09-30 22:41:15'
		),
			array(
				'id' => 2, 'name' => 'Blog',
				'slug' => 'blog', 'link' => '/', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
				'menu_id' => 'public-menu','group_id' => 0, 'parent_id' => 1, 'lft' => 2, 'rght' => 3,
				'created' => '2010-09-30 22:42:49', 'modified' => '2010-09-30 22:42:49'
			),
			array(
				'id' => 4, 'name' => 'About Me',
				'slug' => 'about-me','link' => '/cms/random/about-me', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
				'menu_id' => 'public-menu', 'group_id' => 0, 'parent_id' => 1, 'lft' => 4, 'rght' => 5,
				'created' => '2010-11-09 22:47:09', 'modified' => '2010-11-09 22:47:09'
			),
			array(
				'id' => 3,'name' => 'Sandbox', 'slug' => 'sandbox', 'link' => '', 'prefix' => '',
				'plugin' => 'sandbox', 'controller' => 'sandbox', 'action' => 'index', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 1, 'class' => '', 'active' => 1,
				'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
				'menu_id' => 'public-menu', 'group_id' => 0, 'parent_id' => 1, 'lft' => 6, 'rght' => 7,
				'created' => '2010-11-09 14:09:01', 'modified' => '2010-11-09 14:09:01'
			),
		array(
			'id' => 5, 'name' => 'Main_menu', 'slug' => 'registered_users', 'link' => '',
			'prefix' => '', 'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
			'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 0,
			'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
			'menu_id' => 'registered-menu', 'group_id' => 2, 'parent_id' => null, 'lft' => 9, 'rght' => 14,
			'created' => '2010-09-30 22:41:15', 'modified' => '2010-09-30 22:41:15'
		),
			array(
				'id' => 6, 'name' => 'About Me',
				'slug' => 'about-me','link' => '/cms/random/about-me', 'prefix' => '',
				'plugin' => '', 'controller' => '', 'action' => '', 'params' => '',
				'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
				'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
				'menu_id' => 'registered-menu', 'group_id' => 2, 'parent_id' => 5, 'lft' => 10, 'rght' => 13,
				'created' => '2010-11-09 22:47:09', 'modified' => '2010-11-09 22:47:09'
			),
				array(
					'id' => 7, 'name' => 'Deep link',
					'slug' => 'another','link' => '', 'prefix' => '',
					'plugin' => 'Blog', 'controller' => 'BlogPostsController', 'action' => 'view', 'params' => '{"foo":"bar"}',
					'force_backend' => 0, 'force_frontend' => 0, 'class' => '', 'active' => 1,
					'id_attribute' => null, 'target' => null, 'title' => null, 'image' => null,
					'menu_id' => 'registered-menu', 'group_id' => 2, 'parent_id' => 6, 'lft' => 11, 'rght' => 12,
					'created' => '2010-11-09 22:47:09', 'modified' => '2010-11-09 22:47:09'
				),
	);
}
