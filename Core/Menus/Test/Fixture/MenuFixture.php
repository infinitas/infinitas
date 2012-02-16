<?php
/* CoreMenu Fixture generated on: 2010-03-13 11:03:36 : 1268472216 */
class MenuFixture extends CakeTestFixture {
	var $name = 'Menu';

	var $table = 'core_menus';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'menu_index' => array('column' => array('type', 'active'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Public Users',
			'type' => 'main_menu',
			'item_count' => 0,
			'active' => 1,
			'created' => '2010-02-01 00:35:47',
			'modified' => '2010-02-01 00:35:47'
		),
		array(
			'id' => 2,
			'name' => 'Registered Users',
			'type' => 'registered_users',
			'item_count' => 0,
			'active' => 1,
			'created' => '2010-05-13 18:59:35',
			'modified' => '2010-05-13 18:59:35'
		),
	);
}
?>