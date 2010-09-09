<?php
/* CoreTrash Fixture generated on: 2010-08-17 03:08:19 : 1282012219 */
class TrashFixture extends CakeTestFixture {
	var $name = 'Trash';
	var $table = 'core_trash';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'deleted' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'model' => 'Creditcards.Product',
			'foreign_key' => '16',
			'name' => 'Deleted Credit Card',
			'data' => 'a:1:{s:7:\"Product\";a:17:{s:2:\"id\";s:2:\"16\";s:4:\"name\";s:19:\"Deleted Credit Card\";s:4:\"slug\";s:19:\"deleted-credit-card\";s:4:\"info\";s:36:\"Deleted Credit Card user information\";s:11:\"description\";s:37:\"Deleted Credit Card admin information\";s:5:\"image\";s:23:\"deleted_credit_card.png\";s:14:\"minimum_salary\";s:8:\"99999999\";s:16:\"product_color_id\";s:1:\"1\";s:11:\"supplier_id\";s:1:\"1\";s:6:\"active\";s:1:\"1\";s:6:\"locked\";s:1:\"0\";s:9:\"locked_by\";N;s:12:\"locked_since\";N;s:7:\"deleted\";s:1:\"1\";s:12:\"deleted_date\";s:19:\"2010-03-01 00:00:00\";s:7:\"created\";s:19:\"2010-03-01 00:00:00\";s:8:\"modified\";s:19:\"2010-03-02 00:00:00\";}}',
			'deleted' => '2010-05-24 17:38:07',
			'deleted_by' => NULL
		),
		array(
			'id' => 2,
			'model' => 'Creditcards.Package',
			'foreign_key' => '5',
			'name' => 'deleted package',
			'data' => 'a:1:{s:7:\"Package\";a:22:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:15:\"deleted package\";s:4:\"slug\";s:15:\"deleted package\";s:4:\"info\";s:15:\"deleted package\";s:11:\"description\";s:15:\"deleted package\";s:11:\"supplier_id\";s:1:\"5\";s:17:\"debit_interest_id\";s:1:\"5\";s:18:\"credit_interest_id\";s:1:\"1\";s:10:\"product_id\";s:2:\"10\";s:19:\"subscription_fee_id\";s:1:\"1\";s:6:\"active\";s:1:\"1\";s:6:\"locked\";s:1:\"0\";s:12:\"locked_since\";N;s:9:\"locked_by\";N;s:7:\"deleted\";s:1:\"1\";s:12:\"deleted_date\";s:19:\"2010-03-01 00:00:00\";s:7:\"created\";s:19:\"2010-03-01 00:00:00\";s:8:\"modified\";s:19:\"2010-05-25 14:22:29\";s:21:\"subscription_fee_rank\";s:6:\"604.84\";s:6:\"rating\";s:1:\"1\";s:12:\"rating_count\";s:3:\"100\";s:18:\"included_fee_count\";s:1:\"0\";}}',
			'deleted' => '2010-05-25 15:39:13',
			'deleted_by' => NULL
		),
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
			'id' => 9,
			'model' => 'Think.Supplier',
			'foreign_key' => '4',
			'name' => 'Deleted Supplier',
			'data' => 'a:1:{s:8:\"Supplier\";a:18:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:16:\"Deleted Supplier\";s:4:\"slug\";s:16:\"deleted-supplier\";s:4:\"logo\";s:0:\"\";s:4:\"info\";s:26:\"Deleted Supplier user info\";s:11:\"description\";s:27:\"Deleted Supplier admin info\";s:5:\"phone\";s:9:\"123456789\";s:5:\"email\";s:21:\"deleted@deleted.co.za\";s:6:\"active\";s:1:\"0\";s:13:\"package_count\";s:1:\"0\";s:13:\"product_count\";s:1:\"0\";s:6:\"locked\";s:1:\"0\";s:9:\"locked_by\";N;s:12:\"locked_since\";N;s:7:\"deleted\";s:1:\"1\";s:12:\"deleted_date\";s:19:\"2010-03-05 00:00:00\";s:8:\"modified\";s:19:\"2010-03-01 00:00:00\";s:7:\"created\";s:19:\"2010-03-02 00:00:00\";}}',
			'deleted' => '2010-06-03 12:18:05',
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
?>