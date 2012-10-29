<?php
/**
 * @brief fixture file for Trash tests.
 *
 * @package Trash.Fixture
 * @since 0.9b1
 */
class TrashFixture extends CakeTestFixture {
/**
 * Table name
 *
 * @var string
 */
	public $table = 'core_trash';

/**
 * schema
 *
 * @var string
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 3,
			'model' => 'Management.MenuItem',
			'foreign_key' => '68',
			'name' => 'Blog',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:2:\"68\";s:4:\"name\";s:4:\"Blog\";s:4:\"slug\";s:0:\"\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:4:\"blog\";s:10:\"controller\";s:5:\"posts\";s:6:\"action\";s:5:\"index\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-02-01 00:39:00\";s:8:\"modified\";s:19:\"2010-05-05 02:56:54\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 4,
			'model' => 'Management.MenuItem',
			'foreign_key' => '69',
			'name' => 'Cms',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:2:\"69\";s:4:\"name\";s:3:\"Cms\";s:4:\"slug\";s:0:\"\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:3:\"cms\";s:10:\"controller\";s:10:\"frontpages\";s:6:\"action\";s:5:\"index\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-02-01 00:40:00\";s:8:\"modified\";s:19:\"2010-05-05 02:56:49\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 5,
			'model' => 'Management.MenuItem',
			'foreign_key' => '115',
			'name' => 'Shop',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:3:\"115\";s:4:\"name\";s:4:\"Shop\";s:4:\"slug\";s:0:\"\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:4:\"shop\";s:10:\"controller\";s:8:\"products\";s:6:\"action\";s:9:\"dashboard\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-05-05 02:51:00\";s:8:\"modified\";s:19:\"2010-05-05 02:53:38\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 6,
			'model' => 'Management.MenuItem',
			'foreign_key' => '70',
			'name' => 'Register',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:2:\"70\";s:4:\"name\";s:8:\"Register\";s:4:\"slug\";s:0:\"\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:10:\"management\";s:10:\"controller\";s:5:\"users\";s:6:\"action\";s:8:\"register\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-02-02 22:59:00\";s:8:\"modified\";s:19:\"2010-05-05 02:56:43\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 7,
			'model' => 'Management.MenuItem',
			'foreign_key' => '72',
			'name' => 'Contact',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:2:\"72\";s:4:\"name\";s:7:\"Contact\";s:4:\"slug\";s:0:\"\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:7:\"contact\";s:10:\"controller\";s:8:\"branches\";s:6:\"action\";s:5:\"index\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-02-10 18:10:00\";s:8:\"modified\";s:19:\"2010-05-05 02:56:37\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 8,
			'model' => 'Management.MenuItem',
			'foreign_key' => '119',
			'name' => 'Login',
			'data' => 'a:1:{s:8:\"MenuItem\";a:20:{s:2:\"id\";s:3:\"119\";s:4:\"name\";s:5:\"Login\";s:4:\"slug\";s:5:\"login\";s:4:\"link\";s:0:\"\";s:6:\"prefix\";s:0:\"\";s:6:\"plugin\";s:10:\"management\";s:10:\"controller\";s:5:\"users\";s:6:\"action\";s:5:\"login\";s:6:\"params\";s:0:\"\";s:13:\"force_backend\";s:1:\"0\";s:14:\"force_frontend\";s:1:\"1\";s:5:\"class\";s:0:\"\";s:6:\"active\";s:1:\"1\";s:7:\"menu_id\";s:1:\"2\";s:8:\"group_id\";s:1:\"0\";s:9:\"parent_id\";s:2:\"97\";s:3:\"lft\";s:1:\"4\";s:4:\"rght\";s:1:\"5\";s:7:\"created\";s:19:\"2010-05-13 20:42:12\";s:8:\"modified\";s:19:\"2010-05-13 20:42:12\";}}',
			'deleted' => '2010-06-03 09:36:15',
			'deleted_by' => NULL
		),
		array(
			'id' => 10,
			'model' => 'Management.Module',
			'foreign_key' => '20',
			'name' => 'Promotion 1',
			'data' => 'a:1:{s:6:\"Module\";a:25:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:11:\"Promotion 1\";s:7:\"content\";s:0:\"\";s:6:\"module\";s:11:\"promotion_1\";s:6:\"config\";s:0:\"\";s:8:\"theme_id\";s:1:\"0\";s:11:\"position_id\";s:1:\"6\";s:8:\"group_id\";s:1:\"2\";s:8:\"ordering\";s:1:\"1\";s:5:\"admin\";s:1:\"0\";s:6:\"active\";s:1:\"0\";s:6:\"locked\";s:1:\"0\";s:9:\"locked_by\";s:1:\"0\";s:12:\"locked_since\";s:1:\"0\";s:12:\"show_heading\";s:1:\"0\";s:4:\"core\";s:1:\"0\";s:6:\"author\";s:9:\"Infinitas\";s:7:\"licence\";s:3:\"MIT\";s:3:\"url\";s:24:\"http://infinitas-cms.org\";s:10:\"update_url\";s:0:\"\";s:7:\"created\";s:19:\"2010-05-07 19:17:15\";s:8:\"modified\";s:19:\"2010-05-07 19:41:57\";s:6:\"plugin\";s:4:\"shop\";s:9:\"list_name\";s:11:\"Promotion 1\";s:9:\"save_name\";s:11:\"promotion_1\";}}',
			'deleted' => '2010-08-04 23:30:12',
			'deleted_by' => 1
		),
	);

}